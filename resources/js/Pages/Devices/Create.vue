<script setup>
import { onBeforeUnmount, onMounted } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import QrcodeVue from 'qrcode.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import FormSection from '@/Components/FormSection.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    pairing: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: '',
});

const submit = () => {
    form.post(route('devices.store'));
};

let poll = null;

const checkPairingStatus = async () => {
    try {
        const { data } = await axios.get(props.pairing.status_url);

        if (data.paired) {
            clearInterval(poll);
            router.visit(route('devices.index'));
        }
    } catch {
        // Ignore transient errors and keep polling.
    }
};

onMounted(() => {
    poll = setInterval(checkPairingStatus, 3000);
});

onBeforeUnmount(() => clearInterval(poll));
</script>

<template>
    <AppLayout title="Add device">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Add device
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium text-gray-900">Scan to pair</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Open the URL-Pusher app on your phone and scan this code. The device is
                        added automatically once it's scanned.
                    </p>
                </div>

                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-md flex flex-col items-center">
                        <QrcodeVue :value="pairing.payload" :size="220" level="M" />
                        <p class="mt-4 text-sm text-gray-500">Waiting for the app to scan…</p>
                    </div>
                </div>
            </div>

            <SectionBorder />

            <FormSection @submitted="submit">
                <template #title>
                    Add manually
                </template>

                <template #description>
                    Prefer to set it up by hand? Create the device here, then link it from the app.
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
