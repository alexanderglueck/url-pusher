<script setup>
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: 'Are you sure?',
    },
    confirmText: {
        type: String,
        default: 'Confirm',
    },
    cancelText: {
        type: String,
        default: 'Cancel',
    },
    processing: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['confirm', 'close']);
</script>

<template>
    <ConfirmationModal :show="show" @close="emit('close')">
        <template #title>
            {{ title }}
        </template>

        <template #content>
            <slot />
        </template>

        <template #footer>
            <SecondaryButton @click="emit('close')">
                {{ cancelText }}
            </SecondaryButton>

            <DangerButton
                class="ms-3"
                :class="{ 'opacity-25': processing }"
                :disabled="processing"
                @click="emit('confirm')"
            >
                {{ confirmText }}
            </DangerButton>
        </template>
    </ConfirmationModal>
</template>
