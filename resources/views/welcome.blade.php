<x-guest-layout>

    <div class="bg-indigo-600">
        <div class="max-w-2xl mx-auto text-center py-16 px-4 sm:py-20 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                <span class="block">Boost your productivity.</span>
                <span class="block">Start using {{ config('app.name') }} today.</span>
            </h2>
            <p class="mt-4 text-lg leading-6 text-indigo-200">Use URL Pusher to quickly push URLs from your browser to your Android device.</p>
            <div class="mt-8 flex justify-center">
                <div class="inline-flex rounded-md shadow">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Login
                    </a>
                </div>
                <div class="ml-3 inline-flex">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                        Sign up
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
