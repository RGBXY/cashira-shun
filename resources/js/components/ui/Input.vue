<template>
    <div>
        <input
            :type="props.type"
            :placeholder="props.placeholder"
            class="outline-none border w-full mb-1 border-gray-200 py-1 px-2 rounded-lg focus:shadow placeholder:text-gray-400"
            v-model="model"
        />
        <p v-if="massage" class="text-sm text-red-500">{{ massage }}</p>
    </div>
</template>

<script setup>
const model = defineModel({
    type: null,
    required: true,
});

const props = defineProps({
    type: {
        type: String,
        required: true,
    },
    disabled: {
        type: String,
    },
    placeholder: {
        type: String,
        required: true,
    },
    massage: String,
});
</script>
