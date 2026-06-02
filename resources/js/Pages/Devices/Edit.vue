<script setup>
import { ref } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FormSection from '@/Components/FormSection.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import ActionMessage from '@/Components/ActionMessage.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const props = defineProps({
    device: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: props.device.name,
});

const update = () => {
    form.put(route('devices.update', props.device.id), {
        preserveScroll: true,
    });
};

const confirmingDelete = ref(false);
const deleting = ref(false);

const confirmDelete = () => {
    deleting.value = true;

    router.delete(route('devices.destroy', props.device.id), {
        onFinish: () => {
            deleting.value = false;
        },
    });
};
</script>

<template>
    <AppLayout title="Edit device">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit device
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <FormSection @submitted="update">
                <template #title>
                    Device name
                </template>

                <template #description>
                    The device name helps you recognize your device among the list of your devices.
                </template>

                <template #form>
                    <div class="col-span-6 sm:col-span-4">
                        <InputLabel for="name" value="Device name" />
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="block mt-1 w-full"
                            required
                            autofocus
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <ActionMessage :on="form.recentlySuccessful" class="me-3">
                        Saved.
                    </ActionMessage>

                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Save
                    </PrimaryButton>
                </template>
            </FormSection>

            <SectionBorder />

            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Delete device</h3>
                    <p class="mt-1 text-sm text-gray-600">Permanently delete this device.</p>
                </div>

                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl text-sm text-gray-600">
                            Once this device is deleted, you can no longer push to it until you create it again.
                        </div>

                        <div class="mt-5">
                            <DangerButton @click="confirmingDelete = true">
                                Delete device
                            </DangerButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="confirmingDelete"
            title="Delete device?"
            confirm-text="Delete device"
            :processing="deleting"
            @confirm="confirmDelete"
            @close="confirmingDelete = false"
        >
            You can no longer push to <span class="font-medium text-gray-900">{{ device.name }}</span> until you create it again.
        </ConfirmModal>
    </AppLayout>
</template>
