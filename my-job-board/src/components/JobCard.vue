<template>
  <div class="card bg-white dark:bg-gray-800 p-6 rounded-xl shadow-modern border border-gray-200 dark:border-gray-700 flex flex-col gap-3">
    <div class="flex items-center gap-4">
      <img :src="job.logo" alt="Company Logo" class="w-12 h-12 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600" loading="lazy" />
      <div>
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ job.title }}</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400">{{ job.company }}</p>
      </div>
    </div>
    <div class="flex flex-wrap gap-2 text-sm text-gray-600 dark:text-gray-400">
      <span class="flex items-center gap-1">
        <svg class="w-4 h-4 text-primary-light dark:text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.243l-4.243-4.243m0 0l-4.243 4.243m4.243-4.243l4.243-4.243m-4.243 4.243L9 7"></path>
        </svg>
        {{ job.location }}
      </span>
      <span class="flex items-center gap-1">
        <svg class="w-4 h-4 text-primary-light dark:text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.104 0-2 .896-2 2s.896 2 2 2 2-.896 2-2-.896-2-2-2zm0 6c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm0 2c-3.314 0-6-2.686-6-6s2.686-6 6-6 6 2.686 6 6-2.686 6-6 6z"></path>
        </svg>
        {{ job.salary.toLocaleString() }} USD
      </span>
      <span class="flex items-center gap-1">
        <svg class="w-4 h-4 text-primary-light dark:text-primary-dark" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        {{ job.postedDate }}
      </span>
    </div>
    <router-link
      :to="`/jobs/${job.id}`"
      class="mt-auto text-center py-2 bg-primary-light dark:bg-primary-dark text-white rounded-lg hover:bg-primary-dark dark:hover:bg-primary-light transition-colors"
    >
      View Details
    </router-link>
  </div>
</template>

<script setup>
defineProps({
  job: {
    type: Object,
    required: true
  }
})
</script>