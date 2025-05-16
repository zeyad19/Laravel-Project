import { defineStore } from 'pinia'
import axios from 'axios'

export const useUsersStore = defineStore('users', {
  state: () => ({
    users: []
  }),
  actions: {
    async fetchUsers() {
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_URL}/users`)
        this.users = response.data
      } catch (error) {
        console.error('Failed to fetch users:', error)
        throw error
      }
    },
    async suspendUser(id) {
      try {
        await axios.post(`${import.meta.env.VITE_API_URL}/users/${id}/suspend`)
        const user = this.users.find(u => u.id === id)
        if (user) user.is_suspended = true
      } catch (error) {
        console.error('Failed to suspend user:', error)
        throw error
      }
    },
    async deleteUser(id) {
      try {
        await axios.delete(`${import.meta.env.VITE_API_URL}/users/${id}`)
        this.users = this.users.filter(u => u.id !== id)
      } catch (error) {
        console.error('Failed to delete user:', error)
        throw error
      }
    }
  }
})