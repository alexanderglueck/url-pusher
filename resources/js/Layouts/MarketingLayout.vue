<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

const page = usePage();

const canLogin = computed(() => page.props.canLogin);
const canRegister = computed(() => page.props.canRegister);
const apkDownloadUrl = computed(() => page.props.apkDownloadUrl);

const navLinks = [
    { name: 'Features', route: 'features' },
    { name: 'How it works', route: 'how-it-works' },
    { name: 'FAQ', route: 'faq' },
];

const isCurrent = (name) => route().current(name);

const year = new Date().getFullYear();
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex flex-col">
        <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between gap-6">
            <Link :href="route('welcome')" class="flex items-center gap-2 text-gray-800 shrink-0">
                <ApplicationLogo class="block h-9 w-auto" />
                <span class="font-semibold text-lg">URL-Pusher</span>
            </Link>

            <nav class="flex items-center gap-4 sm:gap-6">
                <Link
                    v-for="link in navLinks"
                    :key="link.route"
                    :href="route(link.route)"
                    class="hidden sm:inline text-sm focus:outline-none focus:underline"
                    :class="isCurrent(link.route) ? 'text-gray-900 font-semibold' : 'text-gray-600 hover:text-gray-900'"
                >
                    {{ link.name }}
                </Link>

                <a
                    v-if="apkDownloadUrl"
                    :href="apkDownloadUrl"
                    class="hidden md:inline text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:underline"
                >
                    Download
                </a>

                <Link
                    v-if="canLogin"
                    :href="route('login')"
                    class="text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:underline"
                >
                    Log in
                </Link>

                <Link
                    v-if="canLogin && canRegister"
                    :href="route('register')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                >
                    Register
                </Link>
            </nav>
        </header>

        <main class="flex-1">
            <slot />
        </main>

        <footer class="w-full border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto px-6 py-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                <span>&copy; {{ year }} URL-Pusher</span>

                <nav class="flex items-center gap-4 sm:gap-6">
                    <Link
                        v-for="link in navLinks"
                        :key="link.route"
                        :href="route(link.route)"
                        class="hover:text-gray-900 focus:outline-none focus:underline"
                    >
                        {{ link.name }}
                    </Link>
                    <a
                        v-if="apkDownloadUrl"
                        :href="apkDownloadUrl"
                        class="hover:text-gray-900 focus:outline-none focus:underline"
                    >
                        Android app
                    </a>
                </nav>
            </div>
        </footer>
    </div>
</template>
