<template>
    <div class="flex justify-center h-screen py-5">
        <div
            class="max-w-4xl w-full px-8 py-7 h-full flex flex-col gap-8 border border-gray-300 shadow rounded-xl"
        >
            <div class="flex items-center gap-3">
                <Link
                    href="/"
                    class="p-1.5 border border-gray-300 rounded-full text-gray-700 mt-0.5 shadow active:shadow-inner"
                >
                    <PhArrowLeft />
                </Link>
                <h1 class="text-3xl font-semibold">Sales</h1>
            </div>

            <el-tabs v-model="activeName" class="demo-tabs">
                <el-tab-pane label="Transactions" name="transactions">
                    <TransactionTable
                        :transactions="transactions"
                        @open-detail="openDetailModal"
                    />
                </el-tab-pane>

                <el-tab-pane
                    label="Transactions Calculator"
                    name="transactions-cal"
                >
                    <TransactionCalTable
                        :transactions_cal="transactions_cal"
                        @open-detail="openDetailModal"
                    />
                </el-tab-pane>
            </el-tabs>

            <Transition name="fade" mode="out-in">
                <SalesDetailModal
                    v-if="methodStore.modalId === `sales-detail`"
                    :detail="selectedData"
                />
            </Transition>

            <Pagination :pagination="transactions" />
        </div>
    </div>
</template>

<script setup>
import { PhArrowLeft } from "@phosphor-icons/vue";
import { ref, Transition } from "vue";
import { useMethodStore } from "../../stores/method";
import axios from "axios";
import { Link } from "@inertiajs/vue3";
import Pagination from "../../components/ui/Pagination.vue";
import SalesDetailModal from "../../components/features/sales/SalesDetailModal.vue";
import TransactionTable from "./TransactionTable.vue";
import TransactionCalTable from "./TransactionCalTable.vue";

const props = defineProps({
    transactions: Object,
    transactions_cal: Object,
});

const activeName = ref("transactions");

const methodStore = useMethodStore();

const selectedData = ref(null);

// const searchProductBarcode = debounce(() => {
//     if (barcode.value === "") {
//         searchProductName();
//     }

//     router.get(
//         "/products/searchProductBarcode",
//         { barcode: barcode.value },
//         {
//             preserveState: true,
//             preserveScroll: true,
//             only: ["products"],
//         }
//     );
// }, 500);

const openDetailModal = async (id) => {
    const res = await axios.post(`/sales/searchById`, {
        id: id,
    });
    if (res.data) {
        methodStore.modalFnc("sales-detail");
        selectedData.value = res.data.transactions;
    }
};
</script>

<style lang="scss" scoped></style>
