<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from "../lib/axios.ts"
import ClientForm from './ClientForm.vue'
import type { ClientFormData } from "../lib/types.ts"

const emit = defineEmits<{
  (e: 'cancel'): void
}>()

const router = useRouter()

const defaultFormValues: ClientFormData = {
  first_name: '',
  last_name: '',
  email: '',
  phone: ''
}

const form = ref<ClientFormData>({ ...defaultFormValues })

const handleCancel = () => {
  emit('cancel')
}

const handleSubmit = async () => {
  try {
    const response = await api.post('/api/clients', form.value)
    const createdClient = response.data
    alert('Client created successfully.')
    await router.push(`/clients/${createdClient.id}`)
  } catch (error) {
    console.error('Failed to create client:', error)
    alert('An error occurred while creating the client.')
  }
}
</script>

<template>
  <ClientForm
      v-model="form"
      @submit="handleSubmit"
      @cancel="handleCancel"
  />
</template>
