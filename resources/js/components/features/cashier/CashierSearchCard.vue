<template>
    <div class="border p-2 rounded-lg border-gray-300">
        <div>
            <h1 class="text-lg font-semibold">{{ product?.name }}</h1>
            <h1 class="text-gray-900">{{ formatPrice(product?.price) }}</h1>
            <p class="text-gray-600" >{{ product?.unit }}</p>
        </div>
    </div>
</template>

<script setup>
import formatPrice from "../../../core/helper/formatPrice";

const props = defineProps({
    product: Object,
});
</script>

<style lang="scss" scoped></style>
