<template>
    <Drawer>
        <div class="w-full h-full py-5">
            <div class="max-w-lg relative flex flex-col mx-auto w-full h-full">
                <div class="flex-1 flex items-end translate-y-3">
                    <div
                        class="bg-white w-full flex flex-col gap-1 min-h-20 border-x-2 border-t-2 border-gray-200 pb-8 pt-2 px-3"
                    >
                        <div v-if="expression.length > 0">
                            <p v-for="(item, index) in expression" :key="index">
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
                        <Button @click="clear()" class="flex-1">
                            <span><PhArrowsClockwise /></span>
                        </Button>

                        <Button @click="calculate()" class="flex-1">
                            <span>=</span>
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

                    <Button @click="print()" class="flex-1 w-full">
                        <span>Print</span>
                    </Button>
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
import { ref } from "vue";
import formatPrice from "../../../core/helper/formatPrice";
import { notification } from "../../../core/helper/notification";
import { onKeyStroke } from "@vueuse/core";
import { router } from "@inertiajs/vue3";

const methodStore = useMethodStore();

const value1 = ref(null);
const expression = ref([]);
const result = ref(0);

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
            total: result.value,
        },
        {
            onSuccess: () => {
                notification("success", "Data Printed Successfully");
            },
        }
    );
};

const append = (operator) => {
    if (!value1.value) return;

    // Tambahkan angka terlebih dahulu
    expression.value.push(value1.value);

    // Tambahkan operator di baris berikutnya
    if (operator) {
        expression.value.push(operator);
    }

    value1.value = null;
};

const calculate = () => {
    if (value1.value !== null && value1.value !== "") {
        expression.value.push(value1.value);
    }

    try {
        const fullExpression = expression.value.join(" ");
        result.value = eval(fullExpression);

        if (expression.value.length < 1) return;

        submit();
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
onKeyStroke("=", (e) => {
    e.preventDefault();
    calculate();
    value1.value = null;
});
onKeyStroke("enter", (e) => {
    e.preventDefault();
    print();
});
</script>

<!-- <template>
    <Drawer>
        <div class="w-full h-full py-5">
            <div class="max-w-lg relative flex flex-col mx-auto w-full h-full">
                <div class="flex-1 flex items-end translate-y-3">
                    <div
                        class="bg-white w-full flex flex-col gap-1 min-h-20 border-x-2 border-t-2 border-gray-200 pb-8 pt-2 px-3"
                    >
                        <p v-for="value in historyDisplay" :key="value">
                            {{ value }}
                        </p>

                        <div
                            v-if="history.length > 0"
                            class="border-t pt-3 mt-3"
                        >
                            <p>Total : {{ formatPrice(subTotal) }}</p>
                            <p class="text-sm text-gray-500">
                                Memory: {{ formatPrice(memory) }}
                            </p>
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

                    <div class="flex gap-2 h-10">
                        <Button @click="plus" class="flex-1">
                            <PhPlus />
                            <span>Add</span>
                        </Button>
                        <Button @click="clear()" class="flex-1">
                            <PhArrowsClockwise />
                            <span>Clear</span>
                        </Button>
                        <Button @click="memoryPlus" class="flex-1"
                            ><span>M+</span></Button
                        >
                        <Button @click="memoryRecall" class="flex-1"
                            ><span>MR</span></Button
                        >
                        <Button @click="memoryClear" class="flex-1"
                            ><span>MC</span></Button
                        >
                        <Button class="!border-2 bottom-0">
                            <PhClock
                                class="font-bold"
                                weight="bold"
                                size="20"
                            />
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

                    <Button class="flex-1 w-full">
                        <span>Submit</span>
                    </Button>
                </div>
            </div>
        </div>
    </Drawer>
</template>

<script setup>
import {
    PhArrowsClockwise,
    PhClock,
    PhPlus,
    PhPower,
} from "@phosphor-icons/vue";
import { useMethodStore } from "../../../stores/method";
import Drawer from "../../ui/Drawer.vue";
import Button from "../../ui/Button.vue";
import { computed, ref } from "vue";
import { onKeyStroke } from "@vueuse/core";
import formatPrice from "../../../core/helper/formatPrice";

const methodStore = useMethodStore();

const value1 = ref(null);
const cutStat = ref(false);

const history = ref([]);
const operators = ref([]);
const historyDisplay = ref([]);

const memory = ref(0); // ✅ memory M+

const subTotal = computed(() => calculateSubTotal());

const clear = () => {
    value1.value = null;
    cutStat.value = false;
    history.value = [];
    operators.value = [];
    historyDisplay.value = [];
    memory.value = 0;
};

const calculateSubTotal = () => {
    if (history.value.length === 0) return 0;

    let result = history.value[0];
    for (let i = 1; i < history.value.length; i++) {
        const operator = operators.value[i - 1];
        const current = history.value[i];

        if (operator === "+") result += current;
        else if (operator === "-") result -= current;
        else if (operator === "*") result *= current;
        else if (operator === "/") result /= current;
    }
    return result;
};

const plus = () => {
    if (value1.value === null) return;

    history.value.push(value1.value);
    operators.value.push("+");

    if (cutStat.value === false) {
        historyDisplay.value.push(value1.value, "+");
    } else {
        historyDisplay.value.push("+", value1.value);
    }

    value1.value = null;
};

const minus = () => {
    if (value1.value === null) return;

    history.value.push(value1.value);
    operators.value.push("-");

    if (cutStat.value === false) {
        historyDisplay.value.push(value1.value, "-");
    } else {
        historyDisplay.value.push("-", value1.value);
    }

    value1.value = null;
};

const times = () => {
    if (value1.value === null) return;

    history.value.push(value1.value);
    operators.value.push("*");

    if (cutStat.value === false) {
        historyDisplay.value.push(value1.value, "×");
    } else {
        historyDisplay.value.push("×", value1.value);
    }

    value1.value = null;
};

const divide = () => {
    if (value1.value === null) return;

    history.value.push(value1.value);
    operators.value.push("/");

    if (cutStat.value === false) {
        historyDisplay.value.push(value1.value, "÷");
    } else {
        historyDisplay.value.push("÷", value1.value);
    }

    value1.value = null;
};

const cut = () => {
    if (value1.value === null) return;

    history.value.push(value1.value);
    if (!cutStat.value) historyDisplay.value.push(value1.value);
    cutStat.value = true;

    value1.value = null;
};

// ✅ M+ memory
const memoryPlus = () => {
    memory.value += subTotal.value;
};

// ✅ MR recall
const memoryRecall = () => {
    value1.value = memory.value;
};

// ✅ MC clear
const memoryClear = () => {
    memory.value = 0;
};

// 🎹 Keyboard shortcut
onKeyStroke("[", (e) => {
    e.preventDefault();
    plus();
});
onKeyStroke("]", (e) => {
    e.preventDefault();
    minus();
});
onKeyStroke(";", (e) => {
    e.preventDefault();
    times();
});
onKeyStroke("'", (e) => {
    e.preventDefault();
    divide();
});
onKeyStroke("a", (e) => {
    e.preventDefault();
    cut();
});
onKeyStroke("m", (e) => {
    e.preventDefault();
    memoryPlus();
});
onKeyStroke("r", (e) => {
    e.preventDefault();
    memoryRecall();
});
onKeyStroke("c", (e) => {
    e.preventDefault();
    memoryClear();
});
</script> -->
