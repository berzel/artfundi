<script setup lang="ts">
import { ref, defineEmits } from 'vue'
import axios from 'axios'

axios.defaults.baseURL = 'http://localhost'
axios.defaults.withCredentials = true
axios.defaults.withXSRFToken = true

const emit = defineEmits<{
  (e: 'logged-in'): void
}>()

const email = ref('')
const password = ref('')

const handleSubmit = async (event: Event) => {
  event.preventDefault();

  try {
    await axios.post('/api/login', {
      email: email.value,
      password: password.value,
    })

    emit('logged-in')
  } catch (error) {
    console.error('Login failed:', error)
  }
}
</script>

<template>
  <div class="min-vh-100 bg-light d-flex justify-content-center align-items-center">
    <div class="login-container text-center">
      <h2 class="mb-4">Login</h2>

      <form @submit="handleSubmit">
        <div class="mb-3 text-start">
          <label for="email" class="form-label">Email address</label>
          <input
              v-model="email"
              type="email"
              class="form-control"
              id="email"
              placeholder="Enter email"
              required
          />
        </div>
        <div class="mb-3 text-start">
          <label for="password" class="form-label">Password</label>
          <input
              v-model="password"
              type="password"
              class="form-control"
              id="password"
              placeholder="Password"
              required
          />
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
      </form>
    </div>
  </div>
</template>

<style scoped>
input {
  display: block;
  width: 100%;
  min-width: 300px;
}

.login-container {
  max-width: 400px;
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  background-color: white;
}
</style>
