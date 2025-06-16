<script setup lang="ts">
  import LogoutButton from "../components/LogoutButton.vue";

  interface User {
    name: string
    email: string
  }

  defineProps<{
    user: User
  }>()
</script>

<template>
  <div class="container mt-4">
    <h1>Welcome back, {{ user.name }}</h1>
    <LogoutButton />
  </div>
</template>