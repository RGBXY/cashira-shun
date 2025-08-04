<template>
    <div>
        <el-table :data="transactions_cal?.data" style="width: 100%">
            <el-table-column label="Total" width="120">
                <template #default="total">
                    {{ formatPrice(total.row.total) }}
                </template>
            </el-table-column>

            <el-table-column prop="created_at" label="Created At" min-width="150">
                <template #default="created_at">
                    {{ formatDate(created_at.row.created_at) }}
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script setup>
import formatDate from "../../core/helper/formatDate";
import formatPrice from "../../core/helper/formatPrice";

const props = defineProps({
    transactions_cal: Object,
});
</script>

<style lang="scss" scoped></style>
