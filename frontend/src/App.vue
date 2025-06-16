<script setup lang="ts">
  import { ref, onMounted } from 'vue'
  import axios from 'axios'
  import LoginPage from "./pages/LoginPage.vue"
  import HomePage from "./pages/HomePage.vue"
  import LoadingSpinner from "./components/LoadingSpinner.vue";

  axios.defaults.baseURL = 'http://localhost'
  axios.defaults.withCredentials = true
  axios.defaults.withXSRFToken = true

  const user = ref()
  const loading = ref(true)

  const getAuthenticatedUser = async () => {
    try {
      const response = await axios.get('/api/user')
      user.value = response.data
    } catch {
      user.value = null
    }
  }

  onMounted(async () => {
    await axios.get('/sanctum/csrf-cookie')
    await getAuthenticatedUser()
    loading.value = false
  })

  const handleLoggedIn = async () => {
    await getAuthenticatedUser()
  }
</script>

<template>
  <LoadingSpinner v-if="loading" />
  <LoginPage v-else-if="!user"  @logged-in="handleLoggedIn" />
  <HomePage v-else :user="user" />
</template>

<style>
  @import 'bootstrap/dist/css/bootstrap.min.css';
</style>
