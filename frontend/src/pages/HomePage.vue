<script setup lang="ts">
  import AddClientModal from '../components/AddClientModal.vue'
  import {defineProps} from 'vue'
  import type {User} from "../lib/types.ts";
  import Layout from "../components/Layout.vue";
  import DashboardWidgets from "../components/DashboardWidgets.vue";
  import ClientsTable from "../components/ClientsTable.vue";

  interface Props {
    user: User
  }

  defineProps<Props>()
</script>

<template>
  <Layout>
    <h2 class="mb-4">Welcome back, {{ user.name }}</h2>

    <DashboardWidgets />

    <section class="mt-5">
      <div class="d-flex justify-content-between mb-2">
        <h4>Recent Clients</h4>
        <AddClientModal />
      </div>
      <ClientsTable />
    </section>
  </Layout>
</template>
