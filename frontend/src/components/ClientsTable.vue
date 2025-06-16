<script setup lang="ts">
import BaseTable from './BaseTable.vue';
import api from "../lib/axios.ts";
import { ref, onMounted } from "vue";
import BaseModal from "./BaseModal.vue";
import EditClientForm from "./EditClientForm.vue";
import DeleteClientForm from "./DeleteClientForm.vue";

const columns = ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Actions'];
const clients = ref<Array<Record<string, any>>>([]);

async function fetchClients() {
  try {
    const response = await api.get('/api/clients');
    clients.value = response.data.data;
  } catch (error) {
    console.error('Failed to fetch clients:', error);
  }
}

onMounted(fetchClients);

// Edit Modal Controls
const showEditModal = ref(false);
const selectedEditClient = ref<Record<string, any> | null>(null);

const handleEditModalOpen = (client: any) => {
  selectedEditClient.value = { ...client };
  showEditModal.value = true;
};

const handleEditModalClose = () => {
  showEditModal.value = false;
  selectedEditClient.value = null;
};

const handleEditFormSubmit = async (updatedClient: Record<string, any>) => {
  const response = await api.put(`/api/clients/${updatedClient.id}`, {...updatedClient});
  const index = clients.value.findIndex(c => c.id === updatedClient.id);
  if (index !== -1) {
    clients.value[index] = response.data;
  }
  handleEditModalClose();
};

// Delete Modal Controls
const showDeleteModal = ref(false);
const selectedDeleteClient = ref<Record<string, any> | null>(null);

const handleDeleteModalOpen = (client: any) => {
  selectedDeleteClient.value = { ...client };
  showDeleteModal.value = true;
};

const handleDeleteModalClose = () => {
  showDeleteModal.value = false;
  selectedDeleteClient.value = null;
};

const handleDeleteFormSubmit = async (updatedClient: Record<string, any>) => {
  await api.delete(`/api/clients/${updatedClient.id}`);
  const index = clients.value.findIndex(c => c.id === updatedClient.id);
  if (index !== -1) {
    clients.value = clients.value.filter(c => c.id !== updatedClient.id);
  }
  handleDeleteModalClose();
};

</script>

<template>
  <BaseTable :columns="columns" :rows="clients">
    <template #row="{row}">
      <td>{{ row.id }}</td>
      <td>{{ row.first_name }}</td>
      <td>{{ row.last_name }}</td>
      <td>{{ row.email }}</td>
      <td>{{ row.phone }}</td>

      <td>
        <button @click="handleEditModalOpen(row)">Edit</button>
        <button @click="handleDeleteModalOpen(row)">Delete</button>
      </td>
    </template>
  </BaseTable>

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
</template>