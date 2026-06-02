<script setup>
import { computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import MarketingLayout from '@/Layouts/MarketingLayout.vue';

const page = usePage();
const apkDownloadUrl = computed(() => page.props.apkDownloadUrl);

const faqs = [
    {
        question: 'What is URL-Pusher?',
        answer: 'URL-Pusher is a tool for sending links from your browser straight to your Android phone, where they arrive as push notifications. It also keeps a searchable history of everything you have pushed.',
    },
    {
        question: 'What do I need to get started?',
        answer: 'A URL-Pusher account and the Android app installed on at least one phone. Once your phone is paired, you can start pushing links right away.',
    },
    {
        question: 'How do I link my phone?',
        answer: 'In the dashboard, add a device to generate a one-time QR code, then scan it with the Android app. The code expires automatically, so only you can pair your devices.',
    },
    {
        question: 'Can I push a link to more than one phone?',
        answer: 'Yes. You can push a link to a single device, or send it to every device you have linked at once.',
    },
    {
        question: 'What happens to my links if I delete a device?',
        answer: 'Your pushed links are kept. They are no longer tied to the deleted device, but they stay in your history — deleting a device never removes your links.',
    },
    {
        question: 'Can I recover a link I deleted?',
        answer: 'Yes. Deleted links go to the trash, where you can restore them. You can also permanently delete a link from the trash when you are sure you no longer need it.',
    },
    {
        question: 'Is my data private?',
        answer: 'Your links and devices are tied to your account and are only visible to you. See the privacy policy for the full details.',
    },
    {
        question: 'Where do I get the Android app?',
        answer: 'Use the download link in the navigation, or the button below, to install the latest Android build.',
    },
];
</script>

<template>
    <Head title="FAQ" />

    <MarketingLayout>
        <section class="w-full max-w-3xl mx-auto px-6 py-16">
            <div class="text-center">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900">Frequently asked questions</h1>
                <p class="mt-4 text-lg text-gray-600">
                    The things people most often want to know about URL-Pusher.
                </p>
            </div>

            <dl class="mt-12 space-y-4">
                <div
                    v-for="faq in faqs"
                    :key="faq.question"
                    class="bg-white rounded-lg shadow-sm p-6"
                >
                    <dt class="text-lg font-semibold text-gray-900">{{ faq.question }}</dt>
                    <dd class="mt-2 text-sm text-gray-600 leading-relaxed">{{ faq.answer }}</dd>
                </div>
            </dl>

            <div class="mt-12 text-center">
                <a
                    v-if="apkDownloadUrl"
                    :href="apkDownloadUrl"
                    class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                >
                    Download Android app
                </a>

                <p class="mt-6 text-sm text-gray-500">
                    Still curious? Read <Link :href="route('how-it-works')" class="font-semibold text-indigo-700 hover:text-indigo-900">how it works</Link>.
                </p>
            </div>
        </section>
    </MarketingLayout>
</template>
