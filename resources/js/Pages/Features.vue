<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const page = usePage();
const canRegister = computed(() => page.props.canRegister);

const features = [
    {
        title: 'Instant push to your phone',
        description: 'Paste a link in the dashboard and it is delivered to your phone right away through a push notification — no copy-pasting between devices.',
    },
    {
        title: 'Push to one device or all of them',
        description: 'Choose a specific phone, or push a link to every device you have linked at once. Great for sending an article to all your screens.',
    },
    {
        title: 'Effortless QR-code pairing',
        description: 'Add a new phone by scanning a one-time pairing code. Codes expire automatically, so only you can link your devices.',
    },
    {
        title: 'Favorites for the links you reuse',
        description: 'Star the links you reach for often and filter your history down to just your favorites.',
    },
    {
        title: 'Search your full history',
        description: 'Every link you push is kept, searchable, and loaded on demand so older links never get in your way.',
    },
    {
        title: 'Rich link previews',
        description: 'URL-Pusher fetches the title and preview image for a link so you can recognise what you are sending at a glance.',
    },
    {
        title: 'Trash with restore',
        description: 'Removing a link moves it to the trash, where you can restore it later or delete it permanently when you are sure.',
    },
    {
        title: 'Delivery feedback',
        description: 'Each push shows whether it is pending, sent, or failed, so you always know it reached your device.',
    },
];
</script>

<template>
    <Head title="Features" />

    <MarketingLayout>
        <section class="w-full max-w-5xl mx-auto px-6 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900">Everything URL-Pusher can do</h1>
                <p class="mt-4 max-w-2xl mx-auto text-lg text-gray-600">
                    A focused tool for getting links from your browser onto your phone — and keeping them organised.
                </p>
            </div>

            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="bg-white rounded-lg shadow-sm p-6"
                >
                    <h2 class="text-lg font-semibold text-gray-900">{{ feature.title }}</h2>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ feature.description }}</p>
                </div>
            </div>

            <div v-if="canRegister" class="mt-12 text-center">
                <Link
                    :href="route('register')"
                    class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                >
                    Get started
                </Link>
            </div>
        </section>
    </MarketingLayout>
</template>
