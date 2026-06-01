<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});
</script>

<template>
    <Head title="Instantly push URLs to your devices" />

    <div class="min-h-screen bg-gray-100 flex flex-col">
        <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <div class="flex items-center gap-2 text-gray-800">
                <ApplicationLogo class="block h-9 w-auto" />
                <span class="font-semibold text-lg">URL-Pusher</span>
            </div>

            <nav v-if="canLogin" class="flex items-center gap-4">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 hover:text-gray-900 focus:outline-none focus:underline"
                >
                    Log in
                </Link>

                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                >
                    Register
                </Link>
            </nav>
        </header>

        <main class="flex-1 flex items-center">
            <div class="w-full max-w-7xl mx-auto px-6 py-16 text-center">
                <h1 class="text-4xl sm:text-5xl font-bold tracking-tight text-gray-900">
                    Instantly push URLs to your devices
                </h1>

                <p class="mt-6 max-w-2xl mx-auto text-lg text-gray-600">
                    URL-Pusher lets you send links from your browser straight to any of your
                    Android phones in an instant.
                </p>

                <div v-if="canRegister" class="mt-10 flex items-center justify-center gap-4">
                    <Link
                        :href="route('register')"
                        class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                    >
                        Get started
                    </Link>

                    <Link
                        :href="route('login')"
                        class="inline-flex items-center px-6 py-3 bg-white border border-gray-300 rounded-md font-semibold text-sm text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition"
                    >
                        Log in
                    </Link>
                </div>
            </div>
        </main>

        <footer class="w-full max-w-7xl mx-auto px-6 py-8 text-center text-sm text-gray-500">
            URL-Pusher
        </footer>
    </div>
</template>
