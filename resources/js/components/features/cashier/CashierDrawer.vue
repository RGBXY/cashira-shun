<template>
    <Drawer>
        <div class="w-full h-full py-5 flex justify-center gap-4">
            <div class="max-w-md relative flex flex-col gap-4 w-full h-full">
                <div
                    class="w-full h-fit relative surface-elevated shadow !border-2 flex flex-col gap-4 rounded-xl p-3"
                >
                    <input
                        type="text"
                        v-model="barcode"
                        placeholder="Barcode"
                        @change="searchByBarcode()"
                        ref="searchBarcode"
                        class="w-full border-2 border-gray-300 h-14 text-xl px-2 outline-none rounded-lg"
                    />
                    <input
                        type="number"
                        placeholder="Cash"
                        v-model="cash"
                        class="w-full border-2 border-gray-300 h-14 text-xl px-2 outline-none rounded-lg"
                    />

                    <div class="flex gap-2 flex-wrap">
                        <Button @click="clear()" class="flex-1">
                            <span><PhArrowsClockwise /></span>
                        </Button>

                        <Button @click="clear()" class="flex-1">
                            <span>Submit</span>
                        </Button>

                        <Button @click="print()" class="flex-1">
                            <span>Print</span>
                        </Button>

                        <Button
                            class="!border-2 bottom-0"
                            types="danger"
                            @click="methodStore.modalFncClose()"
                        >
                            <PhPower
                                class="font-bold"
                                weight="bold"
                                size="20"
                            />
                        </Button>
                    </div>
                </div>

                <div class="max-w-lg relative flex flex-col w-full">
                    <div
                        class="w-full relative surface-elevated z-20 shadow !border-2 flex flex-col gap-4 rounded-xl p-3"
                    >
                        <input
                            type="text"
                            placeholder="Search"
                            v-model="search"
                            @keydown="searchByName()"
                            class="w-full border-2 border-gray-300 h-14 text-xl px-2 outline-none rounded-lg"
                        />
                    </div>

                    <div class="flex-1 flex items-end -translate-y-3">
                        <div
                            class="bg-white w-full flex flex-col gap-1 min-h-20 border-x-2 border-b-2 border-gray-200 pt-8 pb-6 px-3"
                        >
                            <div
                                v-if="products.length > 0"
                                class="flex flex-col gap-2"
                            >
                                <CashierSearchCard
                                    @click="addOrder(product)"
                                    v-for="product in products"
                                    :key="product.id"
                                    :product="product"
                                />
                            </div>
                            <p class="text-center" v-else>No Sell</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex w-100 h-full">
                <div
                    class="bg-white w-full h-full overflow-y-auto flex flex-col gap-4 rounded-xl border-2 border-gray-200 pb-8 pt-4 px-3"
                >
                    <div
                        class="flex justify-between p-2 border border-gray-300 rounded-lg sticky top-0 bg-white"
                    >
                        <div>
                            <p>Total :</p>
                            <p>Cash :</p>
                            <p>Change:</p>
                        </div>

                        <div>
                            <p>{{ formatPrice(itemPriceTotal) }}</p>
                            <p>{{ formatPrice(cash) }}</p>
                            <p>{{ formatPrice(change) }}</p>
                        </div>
                    </div>

                    <div v-if="cart.length > 0" class="flex flex-col gap-2">
                        <CashierCard
                            v-for="value in cart"
                            :product="value"
                            key="index"
                            :cart="cart"
                        />
                    </div>
                    <p v-else class="text-center mt-10">No Sell</p>
                </div>
            </div>
        </div>
    </Drawer>
</template>

<script setup>
import { PhArrowsClockwise, PhPower } from "@phosphor-icons/vue";
import { useMethodStore } from "../../../stores/method";
import Drawer from "../../ui/Drawer.vue";
import Button from "../../ui/Button.vue";
import { ref, watch } from "vue";
import { notification } from "../../../core/helper/notification";
import { onKeyStroke } from "@vueuse/core";
import CashierCard from "./CashierCard.vue";
import { debounce } from "lodash";
import { useReceipStore } from "../../../stores/receipt";
import { storeToRefs } from "pinia";
import formatPrice from "../../../core/helper/formatPrice";
import CashierSearchCard from "./CashierSearchCard.vue";
import { router } from "@inertiajs/vue3";

const methodStore = useMethodStore();
const receiptStore = useReceipStore();

const { cart, cash } = storeToRefs(receiptStore);

const barcode = ref("");
const search = ref("");
const change = ref(0);
const products = ref([]);
const searchBarcode = ref(null);
const itemPriceTotal = ref(0);

const print = () => {
    if (
        cart.value.length !== 0 &&
        cash.value !== 0 &&
        cash.value >= itemPriceTotal.value
    ) {
        router.post(
            "/print-receipt",
            {
                products: cart.value,
                cash: cash.value,
                change: change.value,
            },
            {
                onSuccess: () => {
                    notification("success", "Data Printed Successfully");
                    clear();
                },
            }
        );
    }
};

const addOrder = (order) => {
    if (order) {
        const onCart = cart.value.find((item) => item.id === order.id);

        if (onCart) {
            onCart.qty++;
        } else {
            const data = {
                ...order,
                qty: 1,
                itemPriceTotal: itemPriceTotal.value,
            };
            cart.value.push({ ...data });
        }
        barcode.value = "";
    }
};

const searchByBarcode = debounce(() => {
    const trimmedBarcode = barcode.value.trim();

    if (trimmedBarcode === "") {
        return;
    }

    axios
        .post("/searchByBarcode", {
            barcode: trimmedBarcode,
        })
        .then((response) => {
            if (response.data.product !== null) {
                addOrder(response.data.product);
            } else {
                notification("error", "Product Not Found");
                barcode.value = "";
                searchBarcode.value.focus();
            }
        })
        .catch((error) => {
            console.error(error);
        });
}, 200);

const searchByName = debounce(() => {
    const trimmedValue = search.value.trim();

    if (products.value.length > 0 && trimmedValue == "") {
        products.value = [];
    }

    if (trimmedValue === "") {
        return;
    }

    axios
        .post("/searchByName", {
            name: trimmedValue,
        })
        .then((response) => {
            if (response.data.product !== null) {
                products.value = response.data.products;
            } else {
                notification("error", "Product Not Found");
            }
        })
        .catch((error) => {
            console.error(error);
        });
}, 200);

const clear = () => {
    barcode.value = "";
    cart.value = [];
    cash.value = 0;
};

const totalPrice = () => {
    itemPriceTotal.value = cart.value.reduce(
        (acc, item) => acc + item.price * item.qty,
        0
    );
};

const changeFnc = () => {
    const validator = cash.value >= itemPriceTotal.value;

    if (cash.value && itemPriceTotal && validator) {
        change.value = cash.value - itemPriceTotal.value;
    } else {
        change.value = 0;
    }
};

watch(
    () => cart.value,
    () => {
        totalPrice();
    },
    { deep: true }
);

watch(
    () => cash.value,
    () => {
        changeFnc();
    },
    { deep: true }
);

onKeyStroke("[", (e) => {
    e.preventDefault();
    append("+");
});
onKeyStroke("]", (e) => {
    e.preventDefault();
    append("-");
});
onKeyStroke(";", (e) => {
    e.preventDefault();
    append("*");
});
onKeyStroke("'", (e) => {
    e.preventDefault();
    append("/");
});
onKeyStroke("=", (e) => {
    e.preventDefault();
    calculate();
    value1.value = null;
});
// onKeyStroke("enter", (e) => {
//     e.preventDefault();

//     // print();
// });
</script>
