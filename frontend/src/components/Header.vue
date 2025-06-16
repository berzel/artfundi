<script setup lang="ts">
  import LogoutButton from "./LogoutButton.vue";

  defineEmits(["open"]);
</script>

<template>
  <nav class="navbar navbar-light bg-white border-bottom px-4 py-3">
    <button @click="$emit('open')" class="d-md-none btn p-0 m-0 me-1">
      <span class="navbar-toggler-icon"></span>
    </button>
    <span class="navbar-brand mb-0 h5">CRM Dashboard</span>
    <div class="ms-auto">
      <LogoutButton />
    </div>
  </nav>
</template>

<style scoped>

</style>