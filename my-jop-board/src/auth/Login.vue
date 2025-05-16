<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
      <form @submit.prevent="login">
        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
          <input
            v-model="form.email"
            type="email"
            id="email"
            class="mt-1 block w-full border border-gray-300 rounded-md p-2"
            required
          />
        </div>
        <div class="mb-6">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            v-model="form.password"
            type="password"
            id="password"
            class="mt-1 block w-full border border-gray-300 rounded-md p-2"
            required
          />
        </div>
        <button
          type="submit"
          class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 mb-4"
        >
          Login
        </button>
      </form>
      <p class="text-center">
        Don't have an account? 
        <router-link to="/register" class="text-blue-500 hover:underline">Register</router-link>
      </p>
    </div>
  </div>
</template>

<script>
import { useAuth } from '@/composables/useAuth'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

export default {
  setup() {
    const { login: authLogin, isAuthenticated } = useAuth()
    const router = useRouter()
    const form = ref({
      email: '',
      password: '',
    })

    const login = async () => {
      console.log('Login button clicked') // للتصحيح
      try {
        await authLogin(form.value.email, form.value.password)
        if (isAuthenticated.value) {
          console.log('Login successful, redirecting to Home')
          router.push('/')
        }
      } catch (error) {
        console.error('Login failed:', error)
        alert('Login failed. Please check your credentials.')
      }
    }

    return { form, login }
  },
}
</script>