<template>
    <div>
        <el-table :data="transactions.data" style="width: 100%">
            <el-table-column label="Cash" min-width="120">
                <template #default="cash">
                    {{ formatPrice(cash.row.cash) }}
                </template>
            </el-table-column>
            <el-table-column label="Total" min-width="120">
                <template #default="total">
                    {{ formatPrice(total.row.total) }}
                </template>
            </el-table-column>
            <el-table-column label="Change" min-width="120">
                <template #default="change">
                    {{ formatPrice(change.row.change) }}
                </template>
            </el-table-column>
            <el-table-column prop="created_at" label="Created At" width="150">
                <template #default="created_at">
                    {{ formatDate(created_at.row.created_at) }}
                </template>
            </el-table-column>
            <el-table-column label="Action" width="150">
                <template #default="action">
                    <div class="flex gap-2">
                        <Button @click="$emit('open-detail', action.row.id)">
                            Detail
                        </Button>
                    </div>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script setup>
import Button from "../../components/ui/Button.vue";
import formatDate from "../../core/helper/formatDate";
import formatPrice from "../../core/helper/formatPrice";

const props = defineProps({
    transactions: Object,
});
</script>

<style lang="scss" scoped></style>
