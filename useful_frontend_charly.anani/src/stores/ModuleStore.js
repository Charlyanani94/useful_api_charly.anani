import { defineStore } from 'pinia'

export const useModuleStore = defineStore('module', {
  state: () => ({
    modules: [],
    loading: false,
    error: null
  }),
  actions: {
    async fetchModules(token) {
      this.loading = true
      this.error = null
      try {
        const res = await fetch('http://localhost:8000/api/modules', {
          headers: {
            'Accept': 'application/json',
            'Authorization': token ? `Bearer ${token}` : ''
          }
        })
        const data = await res.json()
        this.modules = Array.isArray(data) ? data.map(m => ({ ...m, active: !!m.active })) : []
      } catch (e) {
        this.error = 'Impossible de charger les modules.'
        this.modules = []
      } finally {
        this.loading = false
      }
    },
    async toggleModule(module, token) {
      const url = `http://localhost:8000/api/modules/${module.id}/${module.active ? 'deactivate' : 'activate'}`
      try {
        await fetch(url, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Authorization': token ? `Bearer ${token}` : ''
          }
        })
        module.active = !module.active
      } catch {
        this.error = 'Impossible de changer le statut du module.'
      }
    }
  },
	persist: true
})
