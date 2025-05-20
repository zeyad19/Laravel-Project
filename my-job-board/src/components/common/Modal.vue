<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-xl p-6 w-full max-w-lg">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ title }}</h2>
        <button @click="$emit('close')" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      <slot />
    </div>
  </div>
</template>

<script setup>
defineProps({
  isOpen: Boolean,
  title: String
})
defineEmits(['close'])
</script>