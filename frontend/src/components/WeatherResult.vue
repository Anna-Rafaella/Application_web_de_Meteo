<template>
  <div class="result">
    <h3>Météo actuelle à {{ data.city }}</h3>
    <ul>
      <li>🌡 Température : {{ data.temperature }} °C</li>
      <li>💨 Vent : {{ data.windspeed }} km/h</li>
      <li>🕒 Heure : {{ formatTime(data.time) }}</li>
      <li v-if="weatherData.condition">🌥️ Condition : {{ weatherData.condition }}</li>
      <li>🧭 Latitude : {{ weatherData.latitude }}</li>
      <li>🧭 Longitude : {{ weatherData.longitude }}</li>
    </ul>
    <button @click="$emit('save', data.city)">💾 Sauvegarder</button>
  </div>
</template>

<script setup>
defineProps({ data: Object })

function formatTime(dateStr) {
  const date = new Date(dateStr)
  return `${date.toLocaleDateString()} à ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`
}
</script>
