import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
	state: () => ({
		token: null,
		user: null,
	}),

	actions: {
			async login(email, password) {
				const apiUrl = 'http://localhost:8000/api/login'
				let data = null
				let res = null
				try {
					res = await fetch(apiUrl, {
						method: 'POST',
						headers: { 'Content-Type': 'application/json' },
						body: JSON.stringify({ email, password })
					})
					data = await res.json()
				} catch (e) {
					throw new Error('Impossible de contacter le serveur.')
				}
				if (!res.ok) throw new Error(data && data.message ? data.message : 'Erreur de connexion')
				this.token = data.token
				this.user = { id: data.user_id }
			},
			async register(name, email, password) {
				const apiUrl = 'http://localhost:8000/api/register'
				let data = null
				let res = null
				try {
					res = await fetch(apiUrl, {
						method: 'POST',
						headers: { 'Content-Type': 'application/json' },
						body: JSON.stringify({ name, email, password })
					})
					data = await res.json()
				} catch (e) {
					throw new Error('Impossible de contacter le serveur.')
				}
				if (!res.ok) throw new Error(data && data.message ? data.message : "Erreur d'inscription")
				this.token = null
				this.user = {
					id: data.id,
					name: data.name,
					email: data.email,
					created_at: data.created_at
				}
			},

	},
	persist: true
})
