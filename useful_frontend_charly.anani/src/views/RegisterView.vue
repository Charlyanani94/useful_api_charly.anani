<template>
	<div class="min-h-screen flex items-center justify-center bg-gray-100">
		<form @submit.prevent="register" class="bg-white p-8 rounded shadow-md w-full max-w-sm">
			<h2 class="text-2xl font-bold mb-6 text-center">Inscription</h2>
			<div class="mb-4">
				<label class="block mb-1 text-gray-700">Nom</label>
				<input v-model="name" type="text" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring"  />
				<p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
			</div>
			<div class="mb-4">
				<label class="block mb-1 text-gray-700">Email</label>
				<input v-model="email" type="email" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring"  />
				<p v-if="errors.email" class="text-red-500 text-sm mt-1">{{ errors.email }}</p>
			</div>
			<div class="mb-6">
				<label class="block mb-1 text-gray-700">Mot de passe</label>
				<input v-model="password" type="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring"  />
				<p v-if="errors.password" class="text-red-500 text-sm mt-1">{{ errors.password }}</p>
			</div>
			<button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700">S'inscrire</button>
			<div v-if="error" class="mt-4 text-red-500 text-center">{{ error }}</div>
		</form>
	</div>
</template>

<script setup>

import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/AuthStore'
const name = ref('')
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
	const trimmedName = name.value.trim()
	const trimmedEmail = email.value.trim()
	const trimmedPassword = password.value.trim()
	if (!trimmedName) {
		errors.value.name = 'Le nom est requis.'
	}
	if (!trimmedEmail) {
		errors.value.email = "L'email est requis."
	} else if (!validateEmail(trimmedEmail)) {
		errors.value.email = "Le format de l'email est invalide."
	}
	if (!trimmedPassword) {
		errors.value.password = "Le mot de passe est requis."
	} else if (trimmedPassword.length < 6) {
		errors.value.password = "Le mot de passe doit contenir au moins 6 caractères."
	}
	return Object.keys(errors.value).length === 0
}

async function register() {
		error.value = ''
		if (!validateFields()) {
			return
		}
			try {
				await auth.register(name.value.trim(), email.value.trim(), password.value.trim())
				router.push({ name: 'login' })
			} catch (e) {
				if (e && e.message && typeof e.message === 'string') {
					error.value = e.message
				} else {
					error.value = "Une erreur est survenue lors de l'inscription. Veuillez vérifier vos informations ou réessayer plus tard."
				}
			}
}
</script>
