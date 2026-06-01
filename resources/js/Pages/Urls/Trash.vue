<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

defineProps({
    urls: {
        type: Array,
        default: () => [],
    },
});

const restore = (url) => {
    router.patch(route('urls.restore', url.id), {}, { preserveScroll: true });
};

const forceDelete = (url) => {
    if (window.confirm('Permanently delete this link? This cannot be undone.')) {
        router.delete(route('urls.force-delete', url.id), { preserveScroll: true });
    }
};
</script>

<template>
    <AppLayout title="Trash">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Trash
            </h2>
        </template>

        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-4">
                    <Link :href="route('dashboard')" class="text-sm text-gray-600 hover:text-gray-900">
                        &larr; Back to dashboard
                    </Link>
                </div>

                <div v-if="urls.length" class="bg-white overflow-hidden shadow sm:rounded-md">
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
                                        <span class="block font-medium text-gray-900 truncate">{{ url.title }}</span>
                                        <span class="block text-sm text-gray-500 truncate">{{ url.url }}</span>
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
                                            <DropdownLink as="button" @click="restore(url)">
                                                Restore
                                            </DropdownLink>
                                            <DropdownLink as="button" @click="forceDelete(url)">
                                                Delete forever
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>

                            <div class="flex justify-between items-center mt-2 text-sm text-gray-500">
                                <span>{{ url.device.name }}</span>
                                <span>Deleted {{ url.deleted_at_human }}</span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div v-else class="bg-white overflow-hidden shadow sm:rounded-md px-6 py-10 text-center text-gray-500">
                    Trash is empty.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
