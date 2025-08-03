<template>
    <button
        :class="[
            props.types === 'usual' && 'surface-elevated',
            props.types === 'danger' && 'surface-elevated-danger',
            props.types === 'secondary' && 'surface-elevated-secondary',
            'surface-elevated text-gray-500 font-semibold shadow active:shadow-inner h-10 gap-1.5 px-3 w-fit flex items-center justify-center rounded-lg',
        ]"
    >
        <slot></slot>
    </button>
</template>

<script setup>
const props = defineProps({
    types: {
        type: String,
        default: "usual",
        validator: (value) => ["usual", "danger", "secondary"].includes(value),
    },
});
</script>
