<template>
  <div>
    <RouterView />
    <div>
      <toast v-if="store.toast.message.length > 0" :message="store.toast.message" />
    </div>
  </div>
</template>

<script setup>
import { useGlobalStore } from '@/stores/global';
import toast from '@/components/Alert.vue';
const store = useGlobalStore();
</script>