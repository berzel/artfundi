<script setup lang="ts">
  import { ref } from 'vue'
  import ClientForm from './ClientForm.vue'

  const props = defineProps<{
    client: Record<string, any>
  }>()

  const emit = defineEmits<{
    (e: 'submit', updatedClient: Record<string, any>): void
    (e: 'cancel'): void
  }>()

  const form = ref({ ...props.client })

  function handleSubmit() {
    emit('submit', form.value)
  }

  function handleCancel() {
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
