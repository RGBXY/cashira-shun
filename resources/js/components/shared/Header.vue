<template>
    <div
        class="max-w-3xl w-full mx-auto min-h-12 mt-4 flex items-center shadow justify-between gap-2 rounded-xl border-gray-200 border p-2"
    >
        <div class="flex items-center gap-2">
            <div
                class="surface-elevated shadow-inner text-gray-500 font-semibold h-10 px-3 w-fit flex items-center gap-1.5 justify-center rounded-lg"
            >
                <PhClock class="mt-0.5" />
                <span>{{ time }}</span>
            </div>
            <div
                class="surface-elevated shadow-inner text-gray-500 gap-1.5 font-semibold h-10 px-3 w-fit flex items-center justify-center rounded-lg"
            >
                <PhCalendar class="mt-0.5" />
                <span>{{ formattedDate }}</span>
            </div>
        </div>
        <Button>
            <PhCashRegister class="mt-[3px]" />
            <span>Cashira</span>
        </Button>
    </div>
</template>

<script setup>
import { PhCalendar, PhCashRegister, PhClock } from "@phosphor-icons/vue";
import Button from "../ui/Button.vue";
import { onMounted, ref } from "vue";

const time = ref("");

const now = new Date();
const options = {
    weekday: "short",
    day: "2-digit",
    month: "short",
    year: "numeric",
};
const formattedDate = new Intl.DateTimeFormat("en-US", options).format(now);

function updateTime() {
    const now = new Date();
    const options = {
        hour: "2-digit",
        minute: "2-digit",
        hour12: false,
    };
    time.value = new Intl.DateTimeFormat("en-US", options).format(now);
}

// Update setiap detik
setInterval(updateTime, 1000);

onMounted(() => {
    updateTime();
});
</script>
