<?php

namespace App\Controller;

use App\Entity\WeatherRecord;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/weather/api')]
final class WeatherApiController extends AbstractController
{
    #[Route('', name: 'app_weather_api', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $records = $em->getRepository(WeatherRecord::class)->findAll();

        $data = array_map(fn($r) => [
            'id' => $r->getId(),
            'city' => $r->getCity(),
            'temperature' => $r->getTemperature(),
            'condition' => $r->getCondition(),
            'recordedAt' => $r->getRecordedAt()?->format(\DateTime::ATOM)
        ], $records);

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $record = new WeatherRecord();
        $record->setCity($data['city']);
        $record->setTemperature($data['temperature']);
        $record->setCondition($data['condition']);
        $record->setRecordedAt(new \DateTime());

        $em->persist($record);
        $em->flush();

        return $this->json(['message' => 'Weather data saved']);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $record = $em->getRepository(WeatherRecord::class)->find($id);

        if (!$record) {
            return $this->json(['error' => 'Not found'], 404);
        }

        $record->setCity($data['city']);
        $record->setTemperature($data['temperature']);
        $record->setCondition($data['condition']);
        $em->flush();

        return $this->json(['message' => 'Updated']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $record = $em->getRepository(WeatherRecord::class)->find($id);

        if (!$record) {
            return $this->json(['error' => 'Not found'], 404);
        }

        $em->remove($record);
        $em->flush();

        return $this->json(['message' => 'Deleted']);
    }

    #[Route('/search', methods: ['GET'])]
    public function getWeatherFromApi(Request $request, HttpClientInterface $client): JsonResponse
    {
        $city = $request->query->get('city');

        if (!$city) {
            return $this->json(['error' => 'City parameter is required'], 400);
        }

        $geoResponse = $client->request('GET', 'https://geocoding-api.open-meteo.com/v1/search', [
            'query' => [
                'name' => $city,
                'count' => 1
            ]
        ]);

        $geoData = $geoResponse->toArray();

        if (empty($geoData['results'])) {
            return $this->json(['error' => 'City not found'], 404);
        }

        $lat = $geoData['results'][0]['latitude'];
        $lon = $geoData['results'][0]['longitude'];

        $weatherResponse = $client->request('GET', 'https://api.open-meteo.com/v1/forecast', [
            'query' => [
                'latitude' => $lat,
                'longitude' => $lon,
                'current_weather' => true
            ]
        ]);

        $weatherData = $weatherResponse->toArray();

        return $this->json([
            'city' => $city,
            'latitude' => $lat,
            'longitude' => $lon,
            'temperature' => $weatherData['current_weather']['temperature'],
            'condition' => $this->mapWeatherCodeToText($weatherData['current_weather']['weathercode']),
            'windspeed' => $weatherData['current_weather']['windspeed'],
            'time' => $weatherData['current_weather']['time']
        ]);
    }

    #[Route('/save', methods: ['POST'])]
    public function saveWeatherFromApi(Request $request, HttpClientInterface $client, EntityManagerInterface $em): JsonResponse
    {
        $city = $request->query->get('city');

        if (!$city) {
            return $this->json(['error' => 'City parameter is required'], 400);
        }

        $geoResponse = $client->request('GET', 'https://geocoding-api.open-meteo.com/v1/search', [
            'query' => ['name' => $city, 'count' => 1]
        ]);

        $geoData = $geoResponse->toArray();

        if (empty($geoData['results'])) {
            return $this->json(['error' => 'City not found'], 404);
        }

        $lat = $geoData['results'][0]['latitude'];
        $lon = $geoData['results'][0]['longitude'];

        $weatherResponse = $client->request('GET', 'https://api.open-meteo.com/v1/forecast', [
            'query' => [
                'latitude' => $lat,
                'longitude' => $lon,
                'current_weather' => true
            ]
        ]);

        $weatherData = $weatherResponse->toArray();

        $temperature = $weatherData['current_weather']['temperature'];
        $conditionCode = $weatherData['current_weather']['weathercode'];
        $recordedAt = new \DateTime(); // ✅ heure réelle maintenant

        $conditionText = $this->mapWeatherCodeToText($conditionCode);

        $record = new WeatherRecord();
        $record->setCity($city);
        $record->setTemperature($temperature);
        $record->setCondition($conditionText);
        $record->setRecordedAt($recordedAt);

        $em->persist($record);
        $em->flush();

        return $this->json(['message' => "Météo de $city sauvegardée avec succès."]);
    }

    private function mapWeatherCodeToText(int $code): string
    {
        return match ($code) {
            0 => 'Clear sky',
            1 => 'Mainly clear',
            2 => 'Partly cloudy',
            3 => 'Overcast',
            45 => 'Fog',
            48 => 'Depositing rime fog',
            51 => 'Drizzle',
            53 => 'Moderate drizzle',
            55 => 'Dense Drizzle',
            61 => 'Rain',
            63 => 'Moderate rain',
            65 => 'Heavy rain',
            71 => 'Snow',
            73 => 'Moderate snow',
            75 => 'Heavy snow',
            80 => 'Rain showers',
            81 => 'Moderate rain showers',
            82 => 'Heavy rain showers',
            95 => 'Thunderstorm',
            99 => 'Severe thunderstorm',
            default => 'Unknown',
        };
    }
}
