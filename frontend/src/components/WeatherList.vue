<template>
  <div class="saved-list mt-8">
    <h2 class="text-xl font-bold mb-4">📜 Historique des villes sauvegardées</h2>
    <ul class="space-y-3">
      <li
        v-for="item in list"
        :key="item.id"
        class="bg-white p-4 rounded shadow flex justify-between items-center"
      >
        <div>
          <p class="font-medium">{{ item.city }} – {{ item.temperature }}°C</p>
          <p class="text-sm text-gray-600">
            {{ item.condition }} (le {{ formatTime(item.recordedAt) }})
          </p>
        </div>
        <button
          @click="deleteRecord(item.id)"
          class="text-sm text-red-600 hover:underline"
        >
          Supprimer
        </button>
      </li>
    </ul>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import axios from 'axios'

const list = ref([])

const loadData = async () => {
  try {
    const res = await axios.get('http://localhost:8000/weather/api')
    list.value = res.data
  } catch (e) {
    alert("Erreur lors du chargement.")
  }
}

const deleteRecord = async (id) => {
  if (!confirm('Confirmer la suppression ?')) return

  try {
    await axios.delete(`http://localhost:8000/weather/api/${id}`)
    list.value = list.value.filter(item => item.id !== id)
  } catch (e) {
    alert("Erreur lors de la suppression.")
  }
}

onMounted(loadData)

function formatTime(dateStr) {
  const date = new Date(dateStr)
  return `${date.toLocaleDateString()} à ${date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}`
}
</script>
