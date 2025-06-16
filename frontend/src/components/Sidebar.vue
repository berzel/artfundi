<script setup lang="ts">
  defineProps<{
    open: boolean;
  }>();

  defineEmits(['close'])
</script>

<template>
  <div
      @click.prevent="$emit('close')"
      class="bg-dark bg-opacity-50 position-absolute top-0 start-0 end-0 h-100 d-md-none"
      :class="{'d-block': open, 'd-none': !open}"
      style="z-index: 1030"
  >
  </div>

  <div class="sidebar bg-dark text-white p-3 position-absolute top-0 start-0 end-0 h-100  d-md-block" :class="{'d-none': !open }"  style="z-index: 1040; width: 240px;" >
    <div class="mb-4 ms-3">
      <img src="https://artfundi.tech/images/logos/Logo-White.svg" alt="Artfundi" style="max-width: 150px;">
    </div>
    <ul class="nav flex-column">
      <li class="nav-item  mb-2">
        <a href="#" class="nav-link active text-white">Dashboard</a>
      </li>
      <li class="nav-item mb-2">
        <a href="#" class="nav-link text-white">Clients</a>
      </li>
      <li class="nav-item mb-2">
        <a href="#" class="nav-link text-white">Inventory</a>
      </li>
      <li class="nav-item mb-2">
        <a href="#" class="nav-link text-white">Sales</a>
      </li>
    </ul>
  </div>
</template>