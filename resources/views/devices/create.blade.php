<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create device') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class='md:grid md:grid-cols-3 md:gap-6'>
        <x-section-title>
            <x-slot name="title">{{ __('Device name') }}</x-slot>
            <x-slot name="description">{{ __('The device name helps you recognize your device among the list of your devices.') }}</x-slot>
        </x-section-title>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('devices.store') }}" method="post">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    <div class="grid grid-cols-6 gap-6">
                        @include('devices.partials.edit', $device)
                    </div>
                </div>

                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                    <x-button>
                        {{ __('Create') }}
                    </x-button>
                </div>

            </form>
        </div>
    </div>
        </div>
    </div>
</x-app-layout>
