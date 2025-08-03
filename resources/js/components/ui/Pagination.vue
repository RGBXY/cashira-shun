<template>
    <div
        v-if="pagination.last_page > 1"
        class="py-2 mt-10 border border-gray-300 px-2 bg-white rounded-xl flex items-center justify-between"
    >
        <div class="w-[80%] flex items-center flex-wrap gap-3">
            <button
                @click="goToPage(link.label)"
                :disabled="!link.url"
                :href="link.url"
                class="border px-5 py-2 rounded-lg font-semibold"
                :class="{
                    'surface-elevated-secondary': link.active,
                    'surface-elevated': !link.active,
                    'text-zinc-400': !link.url,
                }"
                v-for="link in pagination.links"
                :key="link.label"
                v-html="link.label"
            />
        </div>
        <p class="w-[15%] text-end me-3">
            Showing {{ pagination.from }} to {{ pagination.to }} of
            {{ pagination.total }}
        </p>
    </div>
</template>

<script setup>
import { Link, router } from "@inertiajs/vue3";

const props = defineProps({
    pagination: Object,
});

const goToPage = (page) => {
    const url = new URL(window.location.href);

    if (page.includes("Previous") && props.pagination.prev_page_url) {
        url.searchParams.set("page", props.pagination.current_page - 1);
    } else if (page.includes("Next") && props.pagination.next_page_url) {
        url.searchParams.set("page", props.pagination.current_page + 1);
    } else {
        url.searchParams.set("page", page);
    }

    router.visit(
        url.toString(),
        {},
        { preserveState: true, preserveScroll: true }
    );
};
</script>
