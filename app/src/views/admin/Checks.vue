<template>
  <AdminChecks></AdminChecks>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import AdminChecks from "@/components/admin/Checks.vue";

export default defineComponent({
  name: "admin-checks",
  components: {
    AdminChecks,
  },
});
</script>
