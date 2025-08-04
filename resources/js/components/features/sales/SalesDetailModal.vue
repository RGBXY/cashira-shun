<template>
    <Drawer>
        <div class="flex items-center justify-center h-screen w-full">
            <div
                class="max-w-xl w-full space-y-6 border border-gray-200 p-5 rounded-lg shadow"
            >
                <h1 class="text-2xl">Detail Transaction</h1>

                <el-table :data="detail" style="width: 100%">
                    <el-table-column prop="name" label="Name" width="200" />
                    <el-table-column label="price" width="120">
                        <template #default="price">
                            {{ formatPrice(price.row.price) }}
                        </template>
                    </el-table-column>
                    <el-table-column prop="qty" label="Qty" width="120" />
                    <el-table-column prop="unit" label="Unit" width="120" />
                </el-table>

                <div class="flex items-center gap-2 justify-end">
                    <Button
                        type="button"
                        types="secondary"
                        @click="methodStore.modalFncClose()"
                    >
                        Close
                    </Button>
                </div>
            </div>
        </div>
    </Drawer>
</template>

<script setup>
import Drawer from "../../ui/Drawer.vue";
import Button from "../../ui/Button.vue";
import { useMethodStore } from "../../../stores/method";
import formatPrice from "../../../core/helper/formatPrice";

const methodStore = useMethodStore();

const props = defineProps({
    detail: Array,
});
</script>
