<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Devices') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow sm:rounded-md px-4 py-5  sm:p-6 ">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if($devices->isEmpty())
                    <p><a class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" href="{{ route('devices.create') }}">Add your first device</a></p>
                @else
                    <p><a class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" href="{{ route('devices.create') }}">Add a new device</a></p>
                    <ul class="mt-10">
                        @foreach($devices as $device)
                            <li>
                                <a href="{{ route('devices.edit', $device) }}">{{ $device->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endempty
            </div>
        </div>
    </div>
</x-app-layout>
