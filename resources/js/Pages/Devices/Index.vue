<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    devices: {
        type: Array,
        default: () => [],
    },
});
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
                <div class="bg-white overflow-hidden shadow sm:rounded-md px-4 py-5 sm:p-6">
                    <Link
                        :href="route('devices.create')"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                    >
                        {{ devices.length ? 'Add a new device' : 'Add your first device' }}
                    </Link>

                    <ul v-if="devices.length" class="mt-8 divide-y divide-gray-200">
                        <li v-for="device in devices" :key="device.id" class="py-3 flex items-center justify-between">
                            <Link :href="route('devices.edit', device.id)" class="text-gray-900 hover:text-gray-600">
                                {{ device.name }}
                            </Link>
                            <span
                                v-if="! device.can_push"
                                class="text-xs uppercase tracking-widest text-gray-400"
                            >
                                Not linked
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
