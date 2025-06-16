<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import LoadingSpinner from "./components/LoadingSpinner.vue"
import api from "./lib/axios.ts"

const user = ref()
const loading = ref(true)

const router = useRouter()
const route = useRoute()

const getAuthenticatedUser = async () => {
  try {
    const response = await api.get('/api/user')
    user.value = response.data
  } catch {
    user.value = null
  }
}

onMounted(async () => {
  await api.get('/sanctum/csrf-cookie')
  await getAuthenticatedUser()
  loading.value = false

  if (!user.value && route.path !== '/login') {
    await router.push('/login')
  }
})

const handleLoggedIn = async () => {
  await getAuthenticatedUser()
  loading.value = false

  if (user.value) {
    await router.push('/')
  }
}
</script>

<template>
  <LoadingSpinner v-if="loading" />
  <router-view v-else :user="user" @logged-in="handleLoggedIn" />
</template>

<style>
@import 'bootstrap/dist/css/bootstrap.min.css';
</style>
