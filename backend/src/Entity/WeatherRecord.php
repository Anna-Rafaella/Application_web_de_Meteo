<?php

namespace App\Entity;

use App\Repository\WeatherRecordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeatherRecordRepository::class)]
class WeatherRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: 'float')]
    private ?float $temperature = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $condition = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $recordedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): self
    {
        $this->temperature = $temperature;
        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    public function getRecordedAt(): ?\DateTimeInterface
    {
        return $this->recordedAt;
    }

    public function setRecordedAt(?\DateTimeInterface $recordedAt): self
    {
        $this->recordedAt = $recordedAt;
        return $this;
    }
}
