<script setup lang="ts">
  import { ref } from 'vue'
  import BaseModal from "./BaseModal.vue";
  import AddClientForm from "./AddClientForm.vue";

  const showModal = ref(false)

  const openModal = () => (showModal.value = true)
  const closeModal = () => (showModal.value = false)
</script>

<template>
  <button class="btn btn-primary" @click="openModal" v-show="!showModal">
    Add Client
  </button>

  <BaseModal :show="showModal" title="Add New Client" @close="closeModal">
    <AddClientForm
        @cancel="closeModal"
    />
  </BaseModal>
</template>
