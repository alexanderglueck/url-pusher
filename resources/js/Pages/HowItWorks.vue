<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const page = usePage();
const canRegister = computed(() => page.props.canRegister);
const apkDownloadUrl = computed(() => page.props.apkDownloadUrl);

const steps = [
    {
        title: 'Create your account',
        description: 'Sign up in the browser. Your account holds your linked devices and your push history.',
    },
    {
        title: 'Install the Android app',
        description: 'Download and install URL-Pusher on your phone so it can receive your links as push notifications.',
    },
    {
        title: 'Pair your phone',
        description: 'In the dashboard, add a device to get a QR code. Scan it with the app and your phone is linked — the code is one-time and expires on its own.',
    },
    {
        title: 'Push a link',
        description: 'Paste any URL and push it to one device or to all of them. It arrives on your phone within moments.',
    },
    {
        title: 'Keep it organised',
        description: 'Star favorites, search your history, and restore anything you remove from the trash.',
    },
];
</script>

<template>
    <Head title="How it works" />

    <MarketingLayout>
        <section class="w-full max-w-3xl mx-auto px-6 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900">How URL-Pusher works</h1>
                <p class="mt-4 text-lg text-gray-600">
                    From sign-up to your first push in five short steps.
                </p>
            </div>

            <ol class="mt-12 space-y-6">
                <li
                    v-for="(step, index) in steps"
                    :key="step.title"
                    class="bg-white rounded-lg shadow-sm p-6 flex gap-4"
                >
                    <span class="flex-shrink-0 flex items-center justify-center w-9 h-9 rounded-full bg-gray-800 text-white font-semibold">
                        {{ index + 1 }}
                    </span>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ step.title }}</h2>
                        <p class="mt-1 text-sm text-gray-600 leading-relaxed">{{ step.description }}</p>
                    </div>
                </li>
            </ol>

            <div class="mt-12 flex flex-wrap items-center justify-center gap-4">
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                >
                    Create your account
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
    </MarketingLayout>
</template>
