import { defineStore } from 'pinia'
import axios from 'axios'

export const useApplicationsStore = defineStore('applications', {
  state: () => ({
    applications: []
  }),
  actions: {
    async fetchApplications(jobId) {
      try {
        const response = await axios.get(`${import.meta.env.VITE_API_URL}/jobs/${jobId}/applications`)
        this.applications = response.data
      } catch (error) {
        console.error('Failed to fetch applications:', error)
        throw error
      }
    },
    async apply(jobId, applicationData) {
      try {
        await axios.post(`${import.meta.env.VITE_API_URL}/jobs/${jobId}/apply`, {
          job_id: jobId,
          cover_letter: applicationData.cover_letter,
          applied_at: new Date().toISOString()
        })
      } catch (error) {
        console.error('Failed to apply:', error)
        throw error
      }
    }
  }
})