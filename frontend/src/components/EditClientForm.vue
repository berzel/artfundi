<script setup lang="ts">
import { ref, watch } from 'vue'
import ClientForm from './ClientForm.vue'
import type {Client, ClientFormData} from "../lib/types.ts";

const props = defineProps<{
  client: Client
}>()

const emit = defineEmits<{
  (e: 'submit', updatedClient: Client): void
  (e: 'cancel'): void
}>()

const form = ref<ClientFormData>({ ...props.client })

watch(() => props.client, (newClient) => {
  form.value = { ...newClient }
})

const handleSubmit = () => {
  emit('submit', form.value)
}

const handleCancel = () => {
  emit('cancel')
}
</script>

<template>
  <ClientForm
      v-model="form"
      @submit="handleSubmit"
      @cancel="handleCancel"
  />
</template>
