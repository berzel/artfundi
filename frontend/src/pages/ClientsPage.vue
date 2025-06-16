<script setup lang="ts">
  import type {User} from "../lib/types.ts";
  import {defineProps} from "vue";

  interface Props {
    user: User
  }

  defineProps<Props>()
</script>

<template>
  <div>
    Clients
  </div>
</template>

<style scoped>

</style>