<script setup lang="ts">
  import api from "../lib/axios.ts";

  const logout = async () => {
    try {
      await api.post('/api/logout')
      window.location.reload()
    } catch (error) {
      console.error('Logout failed:', error)
    }
  }
</script>

<template>
  <button class="btn btn-danger" @click="logout">Logout</button>
</template>
