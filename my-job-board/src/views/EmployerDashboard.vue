<template>
  <div class="container mx-auto p-6">
    <div class="bg-white dark:bg-gray-800 p-8 rounded-xl shadow-modern border border-gray-200 dark:border-gray-700">
      <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Employer Dashboard</h1>
      <p class="text-gray-600 dark:text-gray-400 mb-6">Manage your job posts and applications.</p>
      <Button outline class="mb-4" @click="showCreateModal = true">Create New Job Post</Button>

      <!-- Create Job Modal -->
      <Modal :isOpen="showCreateModal" title="Create New Job Post" @close="showCreateModal = false">
        <form @submit.prevent="handleCreateJob" class="space-y-4">
          <div>
            <label for="title" class="text-lg font-medium text-gray-700 dark:text-gray-300">Job Title</label>
            <Input id="title" v-model="newJob.title" placeholder="Enter job title" />
          </div>
          <div>
            <label for="description" class="text-lg font-medium text-gray-700 dark:text-gray-300">Description</label>
            <textarea v-model="newJob.description" placeholder="Enter job description" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600"></textarea>
          </div>
          <div>
            <label for="salary" class="text-lg font-medium text-gray-700 dark:text-gray-300">Salary (USD)</label>
            <Input id="salary" type="number" v-model="newJob.salary" placeholder="Enter salary" />
          </div>
          <div>
            <label for="location" class="text-lg font-medium text-gray-700 dark:text-gray-300">Location</label>
            <Input id="location" v-model="newJob.location" placeholder="Enter location" />
          </div>
          <div>
            <label for="company" class="text-lg font-medium text-gray-700 dark:text-gray-300">Company</label>
            <Input id="company" v-model="newJob.company" placeholder="Enter company name" />
          </div>
          <div>
            <label for="experience" class="text-lg font-medium text-gray-700 dark:text-gray-300">Experience Level</label>
            <select v-model="newJob.experience" id="experience" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
              <option value="entry">Entry Level</option>
              <option value="mid">Mid Level</option>
              <option value="senior">Senior Level</option>
            </select>
          </div>
          <div>
            <label class="flex items-center gap-2">
              <input type="checkbox" v-model="newJob.remote" class="w-5 h-5" />
              <span class="text-gray-700 dark:text-gray-300">Remote</span>
            </label>
          </div>
          <Button type="submit">Create Job</Button>
        </form>
        <Alert :visible="createError" message="Failed to create job" type="error" class="mt-4" />
      </Modal>

      <!-- Edit Job Modal -->
      <Modal :isOpen="showEditModal" title="Edit Job Post" @close="showEditModal = false">
        <form @submit.prevent="handleEditJob" class="space-y-4">
          <div>
            <label for="edit-title" class="text-lg font-medium text-gray-700 dark:text-gray-300">Job Title</label>
            <Input id="edit-title" v-model="selectedJob.title" placeholder="Enter job title" />
          </div>
          <div>
            <label for="edit-description" class="text-lg font-medium text-gray-700 dark:text-gray-300">Description</label>
            <textarea v-model="selectedJob.description" placeholder="Enter job description" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600"></textarea>
          </div>
          <div>
            <label for="edit-salary" class="text-lg font-medium text-gray-700 dark:text-gray-300">Salary (USD)</label>
            <Input id="edit-salary" type="number" v-model="selectedJob.salary" placeholder="Enter salary" />
          </div>
          <div>
            <label for="edit-location" class="text-lg font-medium text-gray-700 dark:text-gray-300">Location</label>
            <Input id="edit-location" v-model="selectedJob.location" placeholder="Enter location" />
          </div>
          <div>
            <label for="edit-company" class="text-lg font-medium text-gray-700 dark:text-gray-300">Company</label>
            <Input id="edit-company" v-model="selectedJob.company" placeholder="Enter company name" />
          </div>
          <div>
            <label for="edit-experience" class="text-lg font-medium text-gray-700 dark:text-gray-300">Experience Level</label>
            <select v-model="selectedJob.experience" id="edit-experience" class="w-full p-3 border rounded-lg dark:bg-gray-700 dark:text-white dark:border-gray-600">
              <option value="entry">Entry Level</option>
              <option value="mid">Mid Level</option>
              <option value="senior">Senior Level</option>
            </select>
          </div>
          <div>
            <label class="flex items-center gap-2">
              <input type="checkbox" v-model="selectedJob.remote" class="w-5 h-5" />
              <span class="text-gray-700 dark:text-gray-300">Remote</span>
            </label>
          </div>
          <Button type="submit">Update Job</Button>
        </form>
        <Alert :visible="editError" message="Failed to update job" type="error" class="mt-4" />
      </Modal>

      <!-- Applicants Modal -->
      <Modal :isOpen="showApplicantsModal" title="Applicants" @close="showApplicantsModal = false">
        <div v-if="applicants.length">
          <div v-for="applicant in applicants" :key="applicant.id" class="p-4 border-b border-gray-200 dark:border-gray-700">
            <p class="text-gray-900 dark:text-white">Name: {{ applicant.candidate.name }}</p>
            <p class="text-gray-600 dark:text-gray-400">Email: {{ applicant.candidate.email }}</p>
            <p class="text-gray-600 dark:text-gray-400">Applied At: {{ new Date(applicant.applied_at).toLocaleDateString() }}</p>
            <p v-if="applicant.cover_letter" class="text-gray-600 dark:text-gray-400">Cover Letter: {{ applicant.cover_letter }}</p>
          </div>
        </div>
        <p v-else class="text-gray-600 dark:text-gray-400">No applicants yet.</p>
      </Modal>

      <!-- Job Posts List -->
      <div class="space-y-4">
        <div v-for="job in jobs" :key="job.id" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg flex justify-between items-center">
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ job.title }}</h3>
            <p class="text-gray-600 dark:text-gray-400">Status: {{ job.status || 'Pending' }}</p>
          </div>
          <div class="flex gap-2">
            <Button outline @click="openEditModal(job)">Edit</Button>
            <Button outline class="border-red-500 text-red-500 hover:bg-red-500 hover:text-white" @click="deleteJob(job.id)">Delete</Button>
            <Button outline @click="viewApplicants(job.id)">View Applicants</Button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useJobsStore } from '../stores/jobs'
import { useApplicationsStore } from '../stores/applications'
import { useAuth } from '../composables/useAuth'
import Button from '../components/common/Button.vue'
import Modal from '../components/common/Modal.vue'
import Input from '../components/common/Input.vue'
import Alert from '../components/common/Alert.vue'

const jobsStore = useJobsStore()
const applicationsStore = useApplicationsStore()
const { user } = useAuth()
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showApplicantsModal = ref(false)
const createError = ref(false)
const editError = ref(false)
const jobs = ref([])
const applicants = ref([])
const selectedJob = ref(null)

const newJob = ref({
  title: '',
  description: '',
  salary: 0,
  location: '',
  company: '',
  experience: 'entry',
  remote: false
})

const handleCreateJob = async () => {
  try {
    await jobsStore.createJob(newJob.value)
    showCreateModal.value = false
    createError.value = false
    fetchJobs()
  } catch (error) {
    createError.value = true
  }
}

const openEditModal = (job) => {
  selectedJob.value = { ...job }
  showEditModal.value = true
}

const handleEditJob = async () => {
  try {
    await jobsStore.updateJob(selectedJob.value.id, selectedJob.value)
    showEditModal.value = false
    editError.value = false
    fetchJobs()
  } catch (error) {
    editError.value = true
  }
}

const deleteJob = async (id) => {
  if (confirm('Are you sure you want to delete this job?')) {
    try {
      await jobsStore.deleteJob(id)
      fetchJobs()
    } catch (error) {
      alert('Failed to delete job')
    }
  }
}

const viewApplicants = async (jobId) => {
  try {
    await applicationsStore.fetchApplications(jobId)
    applicants.value = applicationsStore.applications
    showApplicantsModal.value = true
  } catch (error) {
    alert('Failed to fetch applicants')
  }
}

const fetchJobs = async () => {
  await jobsStore.fetchJobs()
  jobs.value = jobsStore.jobs.filter(job => job.employer_id === user.value?.id)
}

onMounted(fetchJobs)
</script>