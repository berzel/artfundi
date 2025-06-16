<script setup lang="ts">
  import { ref } from 'vue'
  import api from "../lib/axios.ts"
  import ClientForm from './ClientForm.vue'

  const emit = defineEmits<{
    (e: 'cancel'): void
  }>()

  const defaultFormValues = {
    first_name: '',
    last_name: '',
    email: '',
    phone: ''
  }

  const form = ref({ ...defaultFormValues })

  function handleCancel() {
    emit('cancel')
  }

  async function handleSubmit() {
    try {
      await api.post('/api/clients', form.value)
      alert('Client created successfully.')
      form.value = { ...defaultFormValues }
      handleCancel()
    } catch (error) {

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




