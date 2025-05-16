<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
      <h2 class="text-2xl font-bold mb-6 text-center">Register</h2>
      <form @submit.prevent="register">
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
          <input
            v-model="form.name"
            type="text"
            id="name"
            class="mt-1 block w-full border border-gray-300 rounded-md p-2"
            required
          />
        </div>
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
        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input
            v-model="form.password"
            type="password"
            id="password"
            class="mt-1 block w-full border border-gray-300 rounded-md p-2"
            required
          />
        </div>
        <div class="mb-6">
          <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
          <select
            v-model="form.role"
            id="role"
            class="mt-1 block w-full border border-gray-300 rounded-md p-2"
            required
          >
            <option value="candidate">Candidate</option>
            <option value="employer">Employer</option>
            <option value="admin">Admin</option>
          </select>
        </div>
        <button
          type="submit"
          class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 mb-4"
        >
          Register
        </button>
      </form>
      <p class="text-center">
        Already have an account? 
        <router-link to="/login" class="text-blue-500 hover:underline">Login</router-link>
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
    const { register: authRegister, login: authLogin, isAuthenticated } = useAuth()
    const router = useRouter()
    const form = ref({
      name: '',
      email: '',
      password: '',
      role: 'candidate',
    })

    const register = async () => {
      console.log('Register button clicked') // للتصحيح
      try {
        await authRegister(form.value)
        // تسجيل الدخول تلقائيًا بعد التسجيل
        await authLogin(form.value.email, form.value.password)
        if (isAuthenticated.value) {
          console.log('Registration successful, redirecting to Home')
          router.push('/')
        }
      } catch (error) {
        console.error('Registration failed:', error)
        alert('Registration failed. Please try again.')
      }
    }

    return { form, register }
  },
}
</script>