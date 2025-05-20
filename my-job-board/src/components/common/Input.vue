<template>
  <input
    :type="type"
    :id="id"
    :placeholder="placeholder"
    v-model="inputValue"
    class="w-full p-3 mt-2 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600"
  />
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  id: String,
  type: { type: String, default: 'text' },
  placeholder: String,
  modelValue: String
})

const emit = defineEmits(['update:modelValue'])

const inputValue = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})
</script>