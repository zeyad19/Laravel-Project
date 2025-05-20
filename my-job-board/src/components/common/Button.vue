<template>
  <button
    :type="type"
    class="px-6 py-3 rounded-lg font-semibold transition-colors"
    :class="[
      outline
        ? 'border border-primary-light dark:border-primary-dark text-primary-light dark:text-primary-dark hover:bg-primary-light dark:hover:bg-primary-dark hover:text-white'
        : 'bg-primary-light dark:bg-primary-dark text-white hover:bg-primary-dark dark:hover:bg-primary-light',
      customClass
    ]"
    :disabled="disabled"
  >
    <slot />
  </button>
</template>

<script setup>
defineProps({
  type: { type: String, default: 'button' },
  outline: Boolean,
  disabled: Boolean,
  customClass: String
})
</script>