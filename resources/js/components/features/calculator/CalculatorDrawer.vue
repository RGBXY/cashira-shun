<template>
    <Drawer>
        <div class="w-full h-full py-5">
            <div class="max-w-lg relative flex flex-col mx-auto w-full h-full">
                <div class="flex-1 flex items-end translate-y-3">
                    <div
                        class="bg-white w-full flex flex-col gap-1 min-h-20 border-x-2 border-t-2 border-gray-200 pb-8 pt-2 px-3"
                    >
                        <div v-if="expression.length > 0">
                            <p
                                v-for="(item, index) in expression"
                                :key="index"
                                :class="{
                                    'italic text-gray-600':
                                        item === '+' || item === '-',
                                    'text-green-700 font-semibold':
                                        typeof item === 'string' &&
                                        item.startsWith('='),
                                    'mt-2':
                                        index > 0 &&
                                        (expression[index - 1] === '+' ||
                                            expression[index - 1] === '-'),
                                }"
                            >
                                {{ item }}
                            </p>
                            <div class="border-t pt-3 mt-3">
                                <p>Total : {{ formatPrice(result) }}</p>
                            </div>
                        </div>
                        <p v-else>No Sell</p>
                    </div>
                </div>

                <div
                    class="w-full h-50 relative surface-elevated shadow !border-2 flex flex-col gap-4 rounded-xl p-3"
                >
                    <input
                        type="number"
                        v-model="value1"
                        class="w-full border-2 border-gray-300 h-14 text-xl px-2 outline-none rounded-lg"
                    />

                    <div class="flex gap-2 flex-wrap">
                        <Button @click="clear" class="flex-1">
                            <span>Clear</span>
                        </Button>

                        <Button @click="calculate" class="flex-1">
                            <span>Calculate</span>
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

                    <Button @click="print" class="flex-1 w-full">
                        <span>Print</span>
                    </Button>
                </div>
            </div>
        </div>
    </Drawer>
</template>

<script setup>
import { ref } from "vue";
import { PhArrowsClockwise, PhPower } from "@phosphor-icons/vue";
import { useMethodStore } from "../../../stores/method";
import Drawer from "../../ui/Drawer.vue";
import Button from "../../ui/Button.vue";
import formatPrice from "../../../core/helper/formatPrice";
import { notification } from "../../../core/helper/notification";
import { onKeyStroke } from "@vueuse/core";
import { router } from "@inertiajs/vue3";

const methodStore = useMethodStore();
const value1 = ref(null);
const expression = ref([]);
const result = ref(0);
const pendingOperator = ref(null);

const submit = () => {
    router.post("/store-calculator", {
        expressions: expression.value,
        total: result.value,
    });
};

const print = () => {
    if (result.value === 0) return;
    router.post(
        "/test-print",
        {
            expressions: expression.value,
            total: formatPrice(result.value),
        },
        {
            onSuccess: () => {
                notification("success", "Data Printed Successfully");
            },
        }
    );
};

const append = (operator) => {
    if (!value1.value) {
        if (operator === "+" || operator === "-") {
            pendingOperator.value = operator;
        }
        return;
    }

    const current = Number(value1.value);

    if (
        expression.value.length >= 2 &&
        (expression.value[expression.value.length - 1] === "*" ||
            expression.value[expression.value.length - 1] === "/")
    ) {
        const prevOperator = expression.value.pop();
        const prevNumber = expression.value.pop();
        const resultCalc =
            prevOperator === "*"
                ? Number(prevNumber) * current
                : Number(prevNumber) / current;
        expression.value.push(`${prevNumber} ${prevOperator} ${current}`);
        expression.value.push(`= ${formatPrice(resultCalc)}`);
        expression.value.push(resultCalc);
    } else {
        if (pendingOperator.value) {
            expression.value.push(pendingOperator.value);
            pendingOperator.value = null;
        }
        expression.value.push(current);
    }

    if (["+", "-", "*", "/"].includes(operator)) {
        expression.value.push(operator);
    }

    value1.value = null;
};

const calculate = () => {
    if (value1.value !== null && value1.value !== "") {
        append("=");
    }

    try {
        const validExpression = expression.value
            .filter(
                (item) =>
                    typeof item === "number" ||
                    ["+", "-", "*", "/"].includes(item)
            )
            .join(" ");
        result.value = eval(validExpression);
        if (expression.value.length > 0) {
            submit();
        }
    } catch (e) {
        result.value = "Error";
    }
};

const clear = () => {
    value1.value = null;
    expression.value = [];
    result.value = 0;
};

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
onKeyStroke("k", (e) => {
    e.preventDefault();
    value1.value *= 100;
});
onKeyStroke("l", (e) => {
    e.preventDefault();
    value1.value *= 1000;
});
onKeyStroke("c", (e) => {
    e.preventDefault();
    clear();
});
onKeyStroke("=", (e) => {
    e.preventDefault();
    calculate();
    value1.value = null;
});
onKeyStroke("p", (e) => {
    e.preventDefault();
    print();
});
</script>
