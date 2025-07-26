<template>
    <div
        class="mx-auto card-style active:!shadow-inner hover:!shadow-inner h-65 w-60 flex items-center justify-center rounded-xl"
    >
        <span class="text-gray-600 font-semibold">{{ prop.tool.name }}</span>
    </div>
</template>

<script setup lang="ts">
type ToolItem = {
    id: number;
    name: string;
    link: string;
};

const prop = defineProps<{
    tool: ToolItem;
}>();
</script>
