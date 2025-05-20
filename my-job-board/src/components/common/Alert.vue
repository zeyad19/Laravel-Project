<template>
  <div v-if="visible" class="p-4 rounded-lg" :class="type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'">
    {{ message }}
  </div>
</template>

<script setup>
defineProps({
  visible: Boolean,
  message: String,
  type: { type: String, default: 'success' }
})
</script>