<script setup lang="ts">
defineProps<{
  columns: string[];
  rows: Array<Record<string, any>>;
}>();
</script>

<template>
  <div class="table-responsive">
    <table class="table table-striped">
      <thead>
      <tr>
        <th v-for="(col, index) in columns" :key="index" scope="col">
          {{ col }}
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="(row, rowIndex) in rows" :key="rowIndex">
        <slot name="row" :row="row" :index="rowIndex">
          <td v-for="(col, colIndex) in columns" :key="colIndex">
            {{ row[col] }}
          </td>
        </slot>
      </tr>
      </tbody>
    </table>
  </div>
</template>