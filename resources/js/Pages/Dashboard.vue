<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const props = defineProps({
    devices: {
        type: Array,
        default: () => [],
    },
    urls: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    url: '',
    device_id: props.devices.length ? props.devices[0].id : null,
});

const copiedId = ref(null);

const pushStatus = {
    sent: { label: 'Delivered', class: 'bg-green-100 text-green-700' },
    failed: { label: 'Failed', class: 'bg-red-100 text-red-700' },
    pending: { label: 'Pending', class: 'bg-gray-100 text-gray-600' },
};

const push = () => {
    form.post(route('urls.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('url'),
    });
};

const pushAgain = (url) => {
    router.post(route('urls.store'), {
        url: url.url,
        device_id: url.device.id,
    }, {
        preserveScroll: true,
    });
};

const copy = (url) => {
    navigator.clipboard.writeText(url.url);
    copiedId.value = url.id;
    setTimeout(() => {
        if (copiedId.value === url.id) {
            copiedId.value = null;
        }
    }, 2000);
};

const destroy = (url) => {
    if (window.confirm('Delete this URL?')) {
        router.delete(route('urls.destroy', url.id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- No pushable devices yet -->
                <div v-if="! devices.length" class="bg-white overflow-hidden shadow sm:rounded-md px-4 py-5 sm:p-6 text-center">
                    <p class="text-gray-600">
                        You don't have any devices ready to receive pushes yet.
                    </p>
                    <Link
                        :href="route('devices.index')"
                        class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                    >
                        Manage devices
                    </Link>
                </div>

                <template v-else>
                    <!-- Push form -->
                    <form @submit.prevent="push">
                        <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-4">
                                    <InputLabel for="url" value="URL" />
                                    <TextInput
                                        id="url"
                                        v-model="form.url"
                                        type="url"
                                        class="block mt-1 w-full"
                                        maxlength="500"
                                        placeholder="https://www.google.com"
                                        required
                                    />
                                    <InputError :message="form.errors.url" class="mt-2" />
                                </div>

                                <div class="col-span-6 sm:col-span-2">
                                    <InputLabel for="device_id" value="Device" />
                                    <select
                                        id="device_id"
                                        v-model="form.device_id"
                                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    >
                                        <option v-for="device in devices" :key="device.id" :value="device.id">
                                            {{ device.name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.device_id" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Push
                            </PrimaryButton>
                        </div>
                    </form>

                    <!-- URL history -->
                    <div v-if="urls.length" class="bg-white overflow-hidden shadow sm:rounded-md mt-10">
                        <ul class="divide-y divide-gray-200">
                            <li v-for="url in urls" :key="url.id" class="px-6 py-4">
                                <div class="flex justify-between">
                                    <div class="flex flex-1 min-w-0 gap-3">
                                        <img
                                            v-if="url.image"
                                            :src="url.image"
                                            alt=""
                                            class="h-12 w-12 shrink-0 rounded object-cover bg-gray-100"
                                            @error="(e) => (e.target.style.display = 'none')"
                                        >
                                        <div class="min-w-0">
                                            <a
                                                :href="url.url"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="block font-medium text-gray-900 truncate"
                                            >
                                                {{ url.title }}
                                            </a>
                                            <a
                                                :href="url.url"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="block text-sm text-gray-500 truncate"
                                            >
                                                {{ url.url }}
                                            </a>
                                            <p v-if="url.description" class="text-sm text-gray-400 truncate">
                                                {{ url.description }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="ms-4">
                                        <Dropdown align="right" width="48">
                                            <template #trigger>
                                                <button type="button" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                                        <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
                                                    </svg>
                                                </button>
                                            </template>

                                            <template #content>
                                                <button
                                                    type="button"
                                                    class="block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition"
                                                    @click="copy(url)"
                                                >
                                                    {{ copiedId === url.id ? 'Copied!' : 'Copy link' }}
                                                </button>

                                                <DropdownLink
                                                    v-if="url.device.can_push"
                                                    as="button"
                                                    @click="pushAgain(url)"
                                                >
                                                    Push again
                                                </DropdownLink>

                                                <DropdownLink as="button" @click="destroy(url)">
                                                    Delete
                                                </DropdownLink>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center mt-2 text-sm text-gray-500">
                                    <span class="flex items-center gap-2">
                                        <span>{{ url.device.name }}</span>
                                        <span
                                            v-if="url.push_status && pushStatus[url.push_status]"
                                            class="px-2 py-0.5 rounded-full text-xs font-medium"
                                            :class="pushStatus[url.push_status].class"
                                        >
                                            {{ pushStatus[url.push_status].label }}
                                        </span>
                                    </span>
                                    <span>{{ url.created_at_human }}</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
