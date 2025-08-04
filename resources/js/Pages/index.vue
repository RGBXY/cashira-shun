<script setup>
import { Transition } from "vue";
import CalculatorDrawer from "../components/features/Calculator/CalculatorDrawer.vue";
import BannerSection from "../components/section/BannerSection.vue";
import ToolCard from "../components/ui/ToolCard.vue";
import { tools_data } from "../core/constan";
import MainLayout from "../layout/MainLayout.vue";
import { useMethodStore } from "../stores/method";
import { Link } from "@inertiajs/vue3";
import CashierDrawer from "../components/features/cashier/CashierDrawer.vue";

defineOptions({ layout: MainLayout });

const methodStore = useMethodStore();
const { modalFnc } = methodStore;
</script>

<template>
    <div class="h-full w-full flex items-center justify-center">
        <div
            class="h-full max-w-3xl w-full text-center py-30 flex flex-col gap-14 items-center justify-center"
        >
            <BannerSection />

            <div class="flex justify-center flex-wrap gap-5">
                <div v-for="tool in tools_data" :key="tool.id">
                    <Link :href="tool.link" v-if="tool.link !== '/'">
                        <ToolCard :tool="tool" />
                    </Link>

                    <ToolCard v-else :tool="tool" @click="modalFnc(tool.id)" />
                </div>
            </div>
        </div>

        <Transition name="fade" mode="out-in">
            <CashierDrawer v-if="methodStore.modalId === 1" />
        </Transition>

        <Transition name="fade" mode="out-in">
            <CalculatorDrawer v-if="methodStore.modalId === 2" />
        </Transition>
    </div>
</template>
