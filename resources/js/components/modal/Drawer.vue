<template>
    <div
        class="h-screen w-full fixed bg-white left-0 top-0 right-0"
        :class="modalStat ? 'opacity-100 visible' : 'invisible opacity-0'"
    >
        halo
    </div>
</template>

<script setup>
import { useMethodStore } from "../../stores/method";
import { storeToRefs } from "pinia";

const methodStore = useMethodStore();
const { modalStat, modalId } = storeToRefs(methodStore);

console.log(modalId, modalStat);
</script>
