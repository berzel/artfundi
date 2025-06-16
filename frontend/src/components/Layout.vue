<script setup lang="ts">
import { ref } from 'vue'
import Sidebar from "../components/Sidebar.vue";
import Header from "../components/Header.vue";

const sidebarOpen = ref(false)
</script>

<template>
  <div>
    <Sidebar @close="sidebarOpen = false" :open="sidebarOpen" />

    <div class="bg-light min-vh-100 main-content">
      <Header @open="sidebarOpen = true" />

      <div class="container-fluid p-4">
        <slot />
      </div>
    </div>
  </div>
</template>
