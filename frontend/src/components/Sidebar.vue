<script setup lang="ts">
  import { useRoute } from 'vue-router'

  defineProps<{
    open: boolean;
  }>()

  defineEmits(['close'])

  const route = useRoute()
</script>

<template>
  <!-- Overlay for mobile -->
  <div
      @click.prevent="$emit('close')"
      class="bg-dark bg-opacity-50 position-absolute top-0 start-0 end-0 h-100 d-md-none"
      :class="{ 'd-block': open, 'd-none': !open }"
      style="z-index: 1030"
  ></div>

  <!-- Sidebar -->
  <div
      class="sidebar bg-dark text-white p-3 position-absolute top-0 start-0 end-0 h-100 d-md-block"
      :class="{ 'd-none': !open }"
      style="z-index: 1040; width: 240px;"
  >
    <div class="mb-4 ms-3">
      <img
          src="https://artfundi.tech/images/logos/Logo-White.svg"
          alt="Artfundi"
          style="max-width: 150px;"
      />
    </div>

    <ul class="nav flex-column">
      <li class="nav-item mb-2">
        <router-link
            to="/"
            class="nav-link text-white"
            :class="{ active: route.path === '/' }"
        >Dashboard</router-link>
      </li>

      <li class="nav-item mb-2">
        <router-link
            to="/clients"
            class="nav-link text-white"
            :class="{ active: route.path.startsWith('/clients') }"
        >Clients</router-link>
      </li>

      <li class="nav-item mb-2">
        <router-link
            to="/inventory"
            class="nav-link text-white"
            :class="{ active: route.path.startsWith('/inventory') }"
        >Inventory</router-link>
      </li>

      <li class="nav-item mb-2">
        <router-link
            to="/sales"
            class="nav-link text-white"
            :class="{ active: route.path.startsWith('/sales') }"
        >Sales</router-link>
      </li>
    </ul>
  </div>
</template>

