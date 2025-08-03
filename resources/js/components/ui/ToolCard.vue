<template>
    <div
        class="mx-auto surface-elevated transition-all active:shadow-inner shadow !border-2 hover:!border-gray-400 hover:!shadow-inner h-65 w-60 flex items-center justify-center rounded-xl"
    >
        <div>
            <img class="w-40" :src="tool.icon" />
            <span class="text-gray-600 font-semibold">{{
                prop.tool.name
            }}</span>
        </div>
    </div>
</template>

<script setup lang="ts">
type ToolItem = {
    id: number;
    name: string;
    link: string;
    icon: string;
};

const prop = defineProps<{
    tool: ToolItem;
}>();
</script>
