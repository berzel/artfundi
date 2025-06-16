<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import type { Client } from "../lib/types.ts"
import Layout from "../components/Layout.vue"
import api from "../lib/axios.ts"
import EditClientForm from "../components/EditClientForm.vue";
import BaseModal from "../components/BaseModal.vue";
import {useModal} from "../lib/useModal.ts";
import DeleteClientForm from "../components/DeleteClientForm.vue";

const route = useRoute()
const router = useRouter()
const clientId = route.params.id as string

const client = ref<Client | null>(null)
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(async () => {
  try {
    const response = await api.get(`/api/clients/${clientId}`)
    client.value = response.data.data
  } catch (err) {
    error.value = 'Failed to load client details.'
    console.error(err)
  } finally {
    loading.value = false
  }
})

const {
  show: showEditModal,
  selected: selectedEditClient,
  open: handleEditModalOpen,
  close: handleEditModalClose
} = useModal<Client>();

const handleEditFormSubmit = async (updatedClient: Client) => {
  try {
    const { data } = await api.put(`/api/clients/${updatedClient.id}`, updatedClient);
    client.value = data;
    handleEditModalClose();
  } catch (error) {
    console.error('Edit failed:', error);
  }
};

const {
  show: showDeleteModal,
  selected: selectedDeleteClient,
  open: handleDeleteModalOpen,
  close: handleDeleteModalClose
} = useModal<Client>();

const handleDeleteFormSubmit = async (clientToDelete: Client) => {
  try {
    await api.delete(`/api/clients/${clientToDelete.id}`);
    alert(`Client Deleted ${clientToDelete.id}`);
    router.push('/clients');
  } catch (error) {
    console.error('Delete failed:', error);
  }
};
</script>

<template>
  <Layout>
    <div v-if="loading">Loading client details...</div>

    <div v-else-if="error">{{ error }}</div>

    <div v-else-if="client">
      <h2>{{ client.first_name }} {{ client.last_name }}</h2>
      <p class="mt-4"><strong>Email:</strong> {{ client.email }}</p>
      <p><strong>Phone:</strong> {{ client.phone }}</p>

      <button @click="handleEditModalOpen(client)">Edit</button>

      <BaseModal
          v-if="selectedEditClient"
          :show="showEditModal"
          @close="handleEditModalClose"
          title="Edit Client"
      >
        <EditClientForm
            :client="selectedEditClient"
            @cancel="handleEditModalClose"
            @submit="handleEditFormSubmit"
        />
      </BaseModal>

      <button @click="handleDeleteModalOpen(client)">Delete</button>

      <BaseModal
          v-if="selectedDeleteClient"
          :show="showDeleteModal"
          @close="handleDeleteModalClose"
          title="Delete Client"
      >
        <DeleteClientForm
            :client="selectedDeleteClient"
            @cancel="handleDeleteModalClose"
            @submit="handleDeleteFormSubmit"
        />
      </BaseModal>
    </div>
    <div v-else>
      <p>Client not found.</p>
    </div>
  </Layout>
</template>
