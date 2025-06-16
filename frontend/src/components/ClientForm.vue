<script setup lang="ts">
  import { reactive, watch } from 'vue'

  const props = defineProps<{
    modelValue: {
      first_name: string
      last_name: string
      email: string
      phone: string
    }
  }>()

  const emit = defineEmits<{
    (e: 'update:modelValue', value: typeof props.modelValue): void
    (e: 'submit'): void
    (e: 'cancel'): void
  }>()

  const localForm = reactive({ ...props.modelValue })

  watch(localForm, (newVal) => {
    emit('update:modelValue', { ...newVal })
  })

  function handleSubmit() {
    emit('submit')
  }
</script>

<template>
  <form @submit.prevent="handleSubmit">
    <div class="mb-3">
      <label for="first_name" class="form-label">First Name</label>
      <input v-model="localForm.first_name" type="text" class="form-control" id="first_name" required>
    </div>

    <div class="mb-3">
      <label for="last_name" class="form-label">Last Name</label>
      <input v-model="localForm.last_name" type="text" class="form-control" id="last_name" required>
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input v-model="localForm.email" type="email" class="form-control" id="email" required>
    </div>

    <div class="mb-3">
      <label for="phone" class="form-label">Phone</label>
      <input v-model="localForm.phone" type="text" class="form-control" id="phone" required>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-4">
      <button type="button" class="btn btn-secondary" @click="$emit('cancel')">Cancel</button>
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
</template>
