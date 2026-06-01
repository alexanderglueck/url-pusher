<script setup>
import { useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('devices.store'));
};
</script>

<template>
    <AppLayout title="Create device">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create device
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <FormSection @submitted="submit">
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
                            placeholder="My phone"
                            required
                            autofocus
                        />
                        <InputError :message="form.errors.name" class="mt-2" />
                    </div>
                </template>

                <template #actions>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Create
                    </PrimaryButton>
                </template>
            </FormSection>
        </div>
    </AppLayout>
</template>
