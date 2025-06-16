<script setup lang="ts">
  import type {User} from "../lib/types.ts";
  import {defineProps} from "vue";
  import Layout from "../components/Layout.vue";
  import ClientsTable from "../components/ClientsTable.vue";
  import AddClientModal from "../components/AddClientModal.vue";

  interface Props {
    user: User
  }

  defineProps<Props>()
</script>

<template>
  <Layout>
    <section class="mt-5">
      <div class="d-flex justify-content-between mb-2">
        <h4>All Clients</h4>
        <AddClientModal />
      </div>
      <ClientsTable />
    </section>
  </Layout>
</template>

<style scoped>

</style>