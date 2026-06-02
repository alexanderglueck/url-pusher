<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const page = usePage();

const canRegister = computed(() => page.props.canRegister);
const apkDownloadUrl = computed(() => page.props.apkDownloadUrl);

const features = [
    {
        title: 'Instant push',
        description: 'Send any link from your browser and it opens on your phone moments later.',
    },
    {
        title: 'Push to every device',
        description: 'Fire a link to all of your linked phones at once, or pick a single one.',
    },
    {
        title: 'QR-code pairing',
        description: 'Link a new phone in seconds by scanning a one-time pairing code.',
    },
    {
        title: 'Favorites & history',
        description: 'Keep your handy links starred and search back through everything you have pushed.',
    },
    {
        title: 'Link previews',
        description: 'See the title and image of a link so you always know what you are sending.',
    },
    {
        title: 'Trash & restore',
        description: 'Deleted a link by mistake? Restore it from the trash, or clear it for good.',
    },
];
</script>

<template>
    <Head title="Instantly push URLs to your devices" />

    <MarketingLayout>
        <section class="w-full max-w-7xl mx-auto px-6 py-16 text-center">
            <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-gray-900">
                Instantly push URLs to your devices
            </h1>

            <p class="mt-6 max-w-2xl mx-auto text-lg text-gray-600">
                URL-Pusher lets you send links from your browser straight to any of your
                Android phones in an instant.
            </p>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-4">
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                >
                    Get started
                </Link>

                <a
                    v-if="apkDownloadUrl"
                    :href="apkDownloadUrl"
                    class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                >
                    Download Android app
                </a>
            </div>
        </section>

        <section class="w-full max-w-7xl mx-auto px-6 pb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="bg-white rounded-lg shadow-sm p-6 text-left"
                >
                    <h2 class="text-lg font-semibold text-gray-900">{{ feature.title }}</h2>
                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ feature.description }}</p>
                </div>
            </div>

            <div class="mt-10 text-center">
                <Link :href="route('how-it-works')" class="text-sm font-semibold text-indigo-700 hover:text-indigo-900">
                    See how it works &rarr;
                </Link>
            </div>
        </section>
    </MarketingLayout>
</template>
