<script setup>

import { ref, onMounted } from 'vue'
import { useGlobalStore } from '@/stores/global'

const store = useGlobalStore()


let open = ref(true)

onMounted(() => {
    setTimeout(() => {
        open.value = false
        store.setAlert('', '')
    }, 4000)
});


</script>

<template>
    <div v-if="open" :class="{
        'bg-blue-500': store.toast.type === 'primary',
        'bg-green-500': store.toast.type === 'success',
        'bg-red-500': store.toast.type === 'danger',
        'bg-yellow-500': store.toast.type === 'warning',
        'bg-gray-800': store.toast.type === 'dark',
    }"
        class="fixed top-12 right-0 mt-8 mr-2 shadow-lg mx-auto w-96 max-w-full text-sm bg-clip-padding rounded-lg block mb-3 z-50 bg-opacity-80 border border-gray-200">

        <div
            class="flex font-bold text-white justify-between items-center py-2 px-3 bg-clip-padding border-b border-gray-300 rounded-t-lg ">

            <!-- <Icon v-if="store.toast.type === 'primary'" icon="fa-solid fa-circle-check" class="text-xl" />
            <Icon v-else-if="store.toast.type === 'dark'" icon="fa-solid fa-circle-check" class="text-xl" />
            <Icon v-else-if="store.toast.type === 'success'" icon="fa-solid fa-circle-check" class="text-xl" />
            <Icon v-else-if="store.toast.type === 'danger'" icon="fa-solid fa-circle-xmark" class="text-xl" />
            <Icon v-else="store.toast.type === 'warning'" icon="fa-solid fa-triangle-exclamation" class="text-xl" /> -->

            <p>{{ store.toast.title }}</p>

            <button @click="open = false" class="hover:scale-125"> X </button>
        </div>

        <div class="p-3 rounded-b-lg break-words  text-justify" :class="{
            'bg-blue-100 text-blue-500': store.toast.type === 'primary',
            'bg-green-100 text-green-500': store.toast.type === 'success',
            'bg-red-100 text-red-500': store.toast.type === 'danger',
            'bg-yellow-50 text-yellow-600': store.toast.type === 'warning',
            'bg-gray-100 text-gray-800': store.toast.type === 'dark',
        }">
            {{ store.toast.message }}
        </div>
    </div>

</template>