import { ref, computed } from 'vue'
import axios from 'axios'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'

export function useAuth() {
  const authStore = useAuthStore()
  const { isAuthenticated, user } = storeToRefs(authStore)
  
  // Use environment variable for API URL if available
  const API_URL = import.meta.env.VITE_API_URL || 'http://localhost:8000/api'

  const login = async (email, password) => {
    try {
      const response = await axios.post(`${API_URL}/login`, {
        email,
        password,
      })
      const token = response.data.token
      authStore.setToken(token)
      authStore.setUser(response.data.user)
      return response.data
    } catch (error) {
      console.error('Login error:', error.response?.data || error.message)
      throw new Error(error.response?.data?.message || 'Login failed')
    }
  }

  const register = async ({ name, email, password, role }) => {
    try {
      const response = await axios.post(`${API_URL}/register`, {
        name,
        email,
        password,
        role,
      })
      return response.data
    } catch (error) {
      console.error('Registration error:', error.response?.data || error.message)
      throw new Error(error.response?.data?.message || 'Registration failed')
    }
  }

  const logout = () => {
    authStore.logout()
  }

  return { isAuthenticated, user, login, register, logout }
}
