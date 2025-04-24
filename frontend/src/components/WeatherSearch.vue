<template>
  <div class="search">
    <h2>🔍 Rechercher une ville</h2>
    <input v-model="city" placeholder="Entrez une ville..." />
    <button @click="searchWeather">Rechercher</button>

    <WeatherResult
      v-if="result"
      :data="result"
      @save="saveWeather"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import WeatherResult from './WeatherResult.vue'

const city = ref('')
const result = ref(null)

const searchWeather = async () => {
  try {
    const res = await axios.get(`http://localhost:8000/weather/api/search?city=${city.value}`)
    result.value = res.data
  } catch (e) {
    alert("Erreur lors de la récupération de la météo.")
    result.value = null
  }
}

const saveWeather = async (cityName) => {
  try {
    await axios.post(`http://localhost:8000/weather/api/save?city=${cityName}`)
    alert(`Météo de ${cityName} sauvegardée avec succès !`)
  } catch (e) {
    alert("Erreur lors de la sauvegarde.")
  }
}
</script>

<style scoped>
input {
  padding: 0.5em;
  margin-right: 0.5em;
}
</style>
