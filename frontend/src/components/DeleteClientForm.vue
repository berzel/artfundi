<script setup lang="ts">
  defineProps<{
    client: Record<string, any>
  }>()

  defineEmits(['cancel', 'submit']);
</script>

<template>
  <p class="card-text">
    Are you sure you want to delete
    <strong>{{ client?.email || 'this client' }}</strong>?
    This action cannot be undone.
  </p>
  <div class="d-flex justify-content-end gap-2 mt-4">
    <button
        type="button"
        class="btn btn-secondary"
        @click="$emit('cancel')"
    >
      Cancel
    </button>
    <button
        type="button"
        class="btn btn-danger"
        @click="$emit('submit', client)"
    >
      Delete
    </button>
  </div>
</template>


