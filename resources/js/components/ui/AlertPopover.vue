<template>
    <el-popover
        :visible="props.id === popoverId"
        trigger="manual"
        placement="top"
        :width="180"
    >
        <p>Are you sure to delete this?</p>
        <div style="text-align: right; margin: 0">
            <el-button size="small" text @click="popoverId = null"
                >close
            </el-button>
            <el-button size="small" type="primary" @click="confirmBtn">
                confirm
            </el-button>
        </div>

        <template #reference>
            <slot></slot>
        </template>
    </el-popover>
</template>

<script lang="ts" setup>
import { useMethodStore } from "../../stores/method";
import { storeToRefs } from "pinia";

const props = defineProps({
    func: {
        type: Function,
        required: true,
    },
    id: {
        type: Number,
        required: true,
    },
});

const confirmBtn = () => {
    props.func();
    popoverStat.value = false;
};

const methodStore = useMethodStore();

const { popoverStat, popoverId } = storeToRefs(methodStore);
</script>
