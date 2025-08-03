<template>
    <div
        class="h-screen w-full fixed bg-white/90 backdrop-blur-2xl transition-all left-0 top-0 right-0 z-50"
        :class="modalStat ? 'opacity-100 visible' : 'invisible opacity-0'"
    >
        <slot></slot>
    </div>
</template>

<script setup>
import { useMethodStore } from "../../stores/method";
import { storeToRefs } from "pinia";

const methodStore = useMethodStore();
const { modalStat } = storeToRefs(methodStore);
</script>
