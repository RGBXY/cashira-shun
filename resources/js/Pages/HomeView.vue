<script setup>
import Card from "../components/card/Card.vue";
import Drawer from "../components/modal/Drawer.vue";
import BannerSection from "../components/section/BannerSection.vue";
import MainLayout from "../layout/MainLayout.vue";
import { tools_data } from "../lib/constan";
import { useMethodStore } from "../stores/method";

defineOptions({ layout: MainLayout });

const methodStore = useMethodStore();
const { modalFnc } = methodStore;
</script>

<template>
    <div class="h-full w-full flex items-center justify-center">
        <div
            class="h-full max-w-5xl w-full text-center py-30  flex flex-col gap-14 items-center justify-center"
        >
            <BannerSection />

            <div class="flex justify-center flex-wrap gap-5">
                <Card
                    v-for="tool in tools_data"
                    :key="tool.id"
                    :tool="tool"
                    @click="modalFnc(tool.id)"
                />
            </div>
        </div>

        <Drawer />
    </div>
</template>
