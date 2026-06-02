<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    src: {
        type: String,
        default: null,
    },
    alt: {
        type: String,
        default: '',
    },
});

const failed = ref(false);

// Reset when the source changes so a recycled list row (search, load-more)
// re-attempts the new image instead of staying on the placeholder.
watch(() => props.src, () => {
    failed.value = false;
});
</script>

<template>
    <img
        v-if="src && ! failed"
        :src="src"
        :alt="alt"
        class="h-12 w-12 shrink-0 rounded object-cover bg-gray-100"
        @error="failed = true"
    >
    <div
        v-else
        class="h-12 w-12 shrink-0 rounded bg-gray-100 flex items-center justify-center text-gray-400"
        aria-hidden="true"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244"
            />
        </svg>
    </div>
</template>
