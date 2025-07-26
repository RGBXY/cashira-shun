<template>
    <div
        class="max-w-3xl w-full mx-auto min-h-12 mt-4 flex items-center shadow justify-between gap-2 rounded-xl border-gray-200 border p-2"
    >
        <div class="flex items-center gap-2">
            <div
                class="card-style-inner text-gray-500 font-semibold h-10 px-3 w-fit flex items-center gap-1.5 justify-center rounded-lg"
            >
                <PhClock class="mt-0.5" />
                <span>20:30</span>
            </div>
            <div
                class="card-style-inner text-gray-500 gap-1.5 font-semibold h-10 px-3 w-fit flex items-center justify-center rounded-lg"
            >
                <PhCalendar class="mt-0.5" />
                <span>14 / Dec / 2012</span>
            </div>
        </div>
        <div
            class="card-style text-gray-500 font-semibold active:!shadow-inner h-10 gap-1.5 px-3 w-fit flex items-center justify-center rounded-lg"
        >
            <PhCashRegister class="mt-[3px]" />
            <span>Cashira</span>
        </div>
    </div>
</template>

<script setup>
import {
    PhCalendar,
    PhCashRegister,
    PhClock,
    PhGlobe,
} from "@phosphor-icons/vue";
</script>
