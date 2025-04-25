<template>
  <div class="min-h-screen bg-gradient-to-b from-sky-800 to-indigo-900 text-white p-6 font-sans">
    <div class="max-w-xl mx-auto text-center">
      <h1 class="text-3xl font-bold mb-4 flex items-center justify-center gap-2">
        <span>🌤️ Application Météo</span>
      </h1>

      <div class="flex justify-center items-center mb-6 gap-2">
        <input
          v-model="city"
          type="text"
          placeholder="Entrez une ville"
          class="p-2 rounded w-2/3 text-black"
        />
        <button @click="searchWeather" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
          Rechercher
        </button>
      </div>

      <div v-if="isLoading" class="text-white text-lg font-medium my-4">Chargement...</div>

      <div v-if="errorMessage" class="bg-red-500 text-white p-3 rounded mb-4">
        {{ errorMessage }}
      </div>


      <div v-if="weatherData" class="bg-white/10 rounded-lg p-4 mb-4 text-left">
        <h2 class="text-xl font-semibold mb-2">{{ weatherData.city }}</h2>
        <img :src="weatherIcon" alt="Icône météo" class="w-20 h-20 my-4 mx-auto" />

        <p>🌡️ Température : {{ weatherData.temperature }} °C</p>
        <p>💨 Vent : {{ weatherData.windspeed }} km/h</p>
        <p>🕒 Heure de la dernière mise à jour : {{ formattedTime(weatherData.time) }}</p>
        <p>🌥️ Condition : {{ weatherData.condition }}</p>
        <p>🧭 Latitude : {{ weatherData.latitude }}</p>
        <p>🧭 Longitude : {{ weatherData.longitude }}</p>
        <button @click="saveWeather" class="mt-3 bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded text-white">
          💾 Sauvegarder
        </button>
      </div>

      <hr class="border-gray-400 my-8" />

      <h3 class="text-xl font-semibold mb-4">📜 Historique des villes sauvegardées</h3>

      <div
        v-for="record in savedWeather"
        :key="record.id"
        class="bg-white text-black rounded-lg shadow-md p-4 mb-3 flex justify-between items-center"
      >
        <div>
          <h4 class="font-bold text-lg">{{ record.city }} – {{ record.temperature }}°C</h4>
          <p>{{ record.condition }} (le {{ formatSavedDate(record.recordedAt) }})</p>
        </div>
        <button @click="deleteWeather(record.id)" class="text-red-600 hover:underline">Supprimer</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const city = ref('')
const weatherData = ref(null)
const savedWeather = ref([])
const isLoading = ref(false)
const errorMessage = ref('')


const searchWeather = async () => {
  if (!city.value) return
  isLoading.value = true
  errorMessage.value = ''
  try {
    const res = await axios.get(`http://localhost:8000/weather/api/search?city=${city.value}`)
    weatherData.value = res.data
  } catch (error) {
  if (error.response && error.response.status === 404) {
    errorMessage.value = 'Ville introuvable.'
  } else {
    errorMessage.value = 'Erreur lors de la récupération des données météo.'
  }
  weatherData.value = null
  } finally {
    isLoading.value = false
  }
}

const saveWeather = async () => {
  if (!weatherData.value?.city) return
  await axios.post(`http://localhost:8000/weather/api/save?city=${weatherData.value.city}`)
  await loadSavedWeather()
}

const deleteWeather = async (id) => {
  await axios.delete(`http://localhost:8000/weather/api/${id}`)
  await loadSavedWeather()
}

const loadSavedWeather = async () => {
  const res = await axios.get('http://localhost:8000/weather/api')
  savedWeather.value = res.data
}

const formattedTime = (iso) => {
  const date = new Date(iso)
  return date.toLocaleString('fr-FR', {
    dateStyle: 'short',
    timeStyle: 'short'
  })
}

const formatSavedDate = (iso) => {
  const date = new Date(iso)
  return date.toLocaleString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

import { computed } from 'vue'

const weatherIcon = computed(() => {
  if (!weatherData.value || !weatherData.value.condition) return '/icons/default.png'

  const condition = weatherData.value.condition.toLowerCase()

  if (condition.includes('clear')) return '/icons/sun.png'
  if (condition.includes('cloud')) return '/icons/cloud.png'
  if (condition.includes('rain')) return '/icons/rain.png'
  if (condition.includes('snow')) return '/icons/snow.png'

  return '/icons/default.png'
})

onMounted(loadSavedWeather)
</script>

<style scoped>
html,
body {
  height: 100%;
  margin: 0;
  padding: 0;
}

input::placeholder {
  color: #888;
}
</style>
