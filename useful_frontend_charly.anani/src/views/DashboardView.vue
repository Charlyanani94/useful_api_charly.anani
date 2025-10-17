<template>
  <div class="min-h-screen bg-gray-100 p-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Dashboard Modules</h1>
    <div v-if="moduleStore.modules.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="module in moduleStore.modules" :key="module.id" class="bg-white rounded shadow p-6 flex flex-col items-center">
        <div class="flex items-center w-full justify-between mb-2">
          <span class="text-xl font-semibold">{{ module.name }}</span>
          <button
            :class="[
              'ml-4 px-3 py-1 rounded text-white',
              module.active ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-400 hover:bg-gray-500'
            ]"
            @click="toggleModule(module)"
          >
            {{ module.active ? 'On' : 'Off' }}
          </button>
        </div>
      </div>
    </div>
    <div v-else class="text-center text-gray-500 mt-12 text-lg">Aucun module disponible</div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from '../stores/AuthStore'
import { useModuleStore } from '../stores/ModuleStore'

const auth = useAuthStore()
const moduleStore = useModuleStore()

async function fetchModules() {
  await moduleStore.fetchModules(auth.token)
}

async function toggleModule(module) {
  await moduleStore.toggleModule(module, auth.token)
}

onMounted(fetchModules)
</script>
