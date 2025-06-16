<script setup lang="ts">
  import axios from "axios";

  axios.defaults.baseURL = 'http://localhost'
  axios.defaults.withCredentials = true
  axios.defaults.withXSRFToken = true

  const logout = async () => {
    try {
      await axios.post('/api/logout')
      window.location.reload()
    } catch (error) {
      console.error('Logout failed:', error)
    }
}
</script>

<template>
  <button class="btn btn-danger" @click="logout">Logout</button>
</template>
