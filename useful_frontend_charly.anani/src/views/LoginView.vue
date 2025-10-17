<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <form @submit.prevent="login" class="bg-white p-8 rounded shadow-md w-full max-w-sm">
      <h2 class="text-2xl font-bold mb-6 text-center">Connexion</h2>
      <div class="mb-4">
        <label class="block mb-1 text-gray-700">Email</label>
        <input v-model="email" type="email" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring" />
        <p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
      </div>
      <div class="mb-6">
        <label class="block mb-1 text-gray-700">Mot de passe</label>
        <input v-model="password" type="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring" />
        <p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</p>
      </div>
      <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Se connecter</button>
      <div v-if="error" class="mt-4 text-red-500 text-center">{{ error }}</div>
    </form>
  </div>
</template>

<script setup>

import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/AuthStore'
const email = ref('')
const password = ref('')
const error = ref('')
const errors = ref({})
const auth = useAuthStore()
const router = useRouter()

function validateEmail(email) {
  return /^\S+@\S+\.\S+$/.test(email)
}

function validateFields() {
  errors.value = {}
  const trimmedEmail = email.value.trim()
  const trimmedPassword = password.value.trim()
  if (!trimmedEmail) {
    errors.value.email = "L'email est requis."
  } else if (!validateEmail(trimmedEmail)) {
    errors.value.email = "Le format de l'email est invalide."
  }
  if (!trimmedPassword) {
    errors.value.password = "Le mot de passe est requis."
  }
  return Object.keys(errors.value).length === 0
}

async function login() {
  error.value = ''
  if (!validateFields()) {
    return
  }
  try {
    await auth.login(email.value.trim(), password.value.trim())
    router.push({ name: 'Dashboard' })
  } catch (e) {
    error.value = e.message || 'Erreur de connexion'
  }
}
</script>
