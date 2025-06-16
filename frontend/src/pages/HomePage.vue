<script setup lang="ts">
  import LogoutButton from '../components/LogoutButton.vue'
  import AddClientModal from '../components/AddClientModal.vue'
  import { defineProps } from 'vue'
  import ClientsTable from "../components/ClientsTable.vue";
  import type {User} from "../lib/types.ts";

  interface Props {
    user: User
  }

  const props = defineProps<Props>()
</script>

<template>
  <div class="container mt-4">
    <header class="d-flex justify-content-between align-items-center">
      <h1>Welcome back, {{ props.user.name }}</h1>
      <LogoutButton />
    </header>

    <section class="mt-4">
      <AddClientModal />
    </section>

    <section class="mt-4">
      <ClientsTable />
    </section>
  </div>
</template>

<style scoped>
  .container {
    padding: 1rem;
  }
</style>
