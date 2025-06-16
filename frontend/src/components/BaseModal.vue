<script setup lang="ts">
  interface Props {
    show: boolean
    title: string
  }

  defineProps<Props>()

  const emit = defineEmits<{
    (e: 'close'): void
  }>()

  const handleClose = () => {
    emit('close')
  }
</script>

<template>
  <div
      class="modal fade show d-block"
      tabindex="-1"
      role="dialog"
      v-if="show"
      style="background: rgba(0, 0, 0, 0.5);"
  >
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{{ title }}</h5>
          <button type="button" class="btn-close" @click="handleClose"></button>
        </div>
        <div class="modal-body">
          <slot />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

</style>