<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

defineProps({
    devices: {
        type: Array,
        default: () => [],
    },
});

const destroy = (device) => {
    if (window.confirm('Delete this device? You can no longer push to it until you add it again.')) {
        router.delete(route('devices.destroy', device.id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <AppLayout title="Devices">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Devices
            </h2>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex justify-end mb-4">
                    <Link
                        :href="route('devices.create')"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                    >
                        {{ devices.length ? 'Add a device' : 'Add your first device' }}
                    </Link>
                </div>

                <div v-if="devices.length" class="bg-white overflow-hidden shadow sm:rounded-md">
                    <ul class="divide-y divide-gray-200">
                        <li v-for="device in devices" :key="device.id" class="px-6 py-4">
                            <div class="flex justify-between">
                                <div class="flex-1 min-w-0">
                                    <Link
                                        :href="route('devices.edit', device.id)"
                                        class="block font-medium text-gray-900 truncate hover:text-gray-600"
                                    >
                                        {{ device.name }}
                                    </Link>
                                    <span class="block text-sm text-gray-500">
                                        {{ device.can_push ? 'Ready to receive pushes' : 'Not linked yet' }}
                                    </span>
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
                                            <DropdownLink :href="route('devices.edit', device.id)">
                                                Edit
                                            </DropdownLink>

                                            <DropdownLink as="button" @click="destroy(device)">
                                                Delete
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else class="bg-white overflow-hidden shadow sm:rounded-md px-6 py-10 text-center text-gray-500">
                    You haven't added any devices yet.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
