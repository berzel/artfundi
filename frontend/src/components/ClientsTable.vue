<script setup lang="ts">
import { ref, onMounted } from "vue";
import api from "../lib/axios.ts";

import BaseTable from './BaseTable.vue';
import BaseModal from "./BaseModal.vue";
import EditClientForm from "./EditClientForm.vue";
import DeleteClientForm from "./DeleteClientForm.vue";
import type {Client} from "../lib/types.ts";

const columns = ['ID', 'First Name', 'Last Name', 'Email', 'Phone', 'Actions'];
const clients = ref<Client[]>([]);

const fetchClients = async () => {
  try {
    const { data } = await api.get('/api/clients');
    clients.value = data.data;
  } catch (error) {
    console.error('Failed to fetch clients:', error);
  }
};

onMounted(fetchClients);

function useModal<T>() {
  const show = ref(false);
  const selected = ref<T | null>(null);

  const open = (item: T) => {
    selected.value = { ...item };
    show.value = true;
  };

  const close = () => {
    show.value = false;
    selected.value = null;
  };

  return { show, selected, open, close };
}

// Edit modal state and logic
const {
  show: showEditModal,
  selected: selectedEditClient,
  open: handleEditModalOpen,
  close: handleEditModalClose
} = useModal<Client>();

const handleEditFormSubmit = async (updatedClient: Client) => {
  try {
    const { data } = await api.put(`/api/clients/${updatedClient.id}`, updatedClient);
    const index = clients.value.findIndex(c => c.id === updatedClient.id);
    if (index !== -1) {
      clients.value[index] = data;
    }
    handleEditModalClose();
  } catch (error) {
    console.error('Edit failed:', error);
  }
};

// Delete modal state and logic
const {
  show: showDeleteModal,
  selected: selectedDeleteClient,
  open: handleDeleteModalOpen,
  close: handleDeleteModalClose
} = useModal<Client>();

const handleDeleteFormSubmit = async (clientToDelete: Client) => {
  try {
    await api.delete(`/api/clients/${clientToDelete.id}`);
    clients.value = clients.value.filter(c => c.id !== clientToDelete.id);
    handleDeleteModalClose();
  } catch (error) {
    console.error('Delete failed:', error);
  }
};
</script>

<template>
  <BaseTable :columns="columns" :rows="clients">
    <template #row="{ row }">
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
