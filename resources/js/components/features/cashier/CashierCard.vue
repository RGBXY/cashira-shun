<template>
    <div
        class="flex flex-col gap-1 border rounded-lg p-2 border-gray-300"
    >
        <div class="flex justify-between" >
            <div>
                <h1 class="text-lg font-semibold mb-2">{{ product?.name }}</h1>
            </div>
            <div>
                <h1 class="text-lg font-semibold">
                    {{ formatPrice(itemTotalPrice) }}
                </h1>

                <p class="text-gray-600">
                    Rp.{{ product?.price }} x {{ product.qty }}
                </p>
            </div>
        </div>
        <div class="flex gap-2">
            <el-input-number
                v-model="product.qty"
                :min="0"
                :max="500"
                @change="handleChange()"
            />

            <Button @click="remove()">
                <PhTrash />
            </Button>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import formatPrice from "../../../core/helper/formatPrice";
import Button from "../../ui/Button.vue";
import { PhTrash } from "@phosphor-icons/vue";

const props = defineProps({
    product: Object,
    cart: Object,
});

const handleChange = () => {
    if (props.product.qty === 0) {
        const index = props.cart.findIndex(
            (item) => item.id === props.product.id
        );
        if (index !== -1) {
            props.cart.splice(index, 1);
        }
    }
};

const remove = () => {
    const index = props.cart.findIndex((item) => item.id === props.product.id);
    if (index !== -1) {
        props.cart.splice(index, 1);
    }
};

const itemTotalPrice = ref(props.product.price);

const totalPrice = () => {
    itemTotalPrice.value = props.product?.price * props.product?.qty;
};

watch(
    () => props.cart,
    () => {
        totalPrice();
    },
    { deep: true }
);
</script>

<style lang="scss" scoped></style>
