<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit device') }}
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
            <form action="{{ route('devices.update', $device) }}" method="post">
                <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                    <div class="grid grid-cols-6 gap-6">
                        @method('PUT')
                        @include('devices.partials.edit', $device)
                    </div>
                </div>


                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                    <x-button>
                        {{ __('Edit') }}
                    </x-button>
                </div>

            </form>
        </div>
    </div>

            <x-section-border />

            <div class='md:grid md:grid-cols-3 md:gap-6'>
                <x-section-title>
                    <x-slot name="title">{{ __('Delete device') }}</x-slot>
                    <x-slot name="description">{{ __('Permanently delete device.') }}</x-slot>
                </x-section-title>

                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">


                        <div class="max-w-xl text-sm text-gray-600">
                            {{ __('Once this device is deleted, you can no longer push to it until you create it again.') }}
                        </div>

                        <form action="{{ route('devices.destroy', $device) }}" method="post" class="confirm-delete">
                            @method('DELETE')
                            @csrf
                        <div class="mt-5">
                            <x-danger-button type="submit">
                                {{ __('Delete device') }}
                            </x-danger-button>
                        </div>
                        </form>


                    </div>
                </div>
            </div>


        </div>
    </div>


</x-app-layout>
