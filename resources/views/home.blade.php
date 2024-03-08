<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if( ! $urls->isEmpty())
                <form action="{{ route('urls.store') }}" method="post" id="push-form">
                    @csrf
                    <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                        <div class="grid grid-cols-6 gap-6">

                            <!-- Name -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="url" value="{{ __('URL') }}"/>
                                <x-input id="url" class="block mt-1 w-full" maxlength="500"
                                             placeholder="https://www.google.com" type="url" name="url"
                                             :value="old('url')" required/>
                                <x-input-error for="url" class="mt-2"/>
                            </div>
                            <div class="col-span-2 sm:col-span-2">
                                <x-label for="device_id" value="{{ __('Device') }}"/>
                                <select name="device_id" id="device_id"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        aria-label="Device">
                                    @foreach($devices as $device)
                                        <option value="{{ $device->id }}">{{ $device->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error for="device_id" class="mt-2"/>
                            </div>

                        </div>
                    </div>

                    <div
                        class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                        <x-button type="submit">
                            {{ __('Push') }}
                        </x-button>
                    </div>
                </form>


                <div class="bg-white overflow-hidden shadow sm:rounded-md  mt-10 ">
                    <ul class="list-group list-group-flush divide-y divide-gray-200">
                        @foreach($urls as $url)
                            <li class="list-group-item px-6 py-4">
                                <div class="flex justify-between">
                                    <div class="flex-1 ">
                                        <div>
                                            <a class="" href="{{ $url->url }}" target="_blank"
                                               rel="noopener noreferrer">
                                                {{ $url->title }}
                                            </a>
                                        </div>
                                        <div>
                                            <a class="text-body" href="{{ $url->url }}" target="_blank"
                                               rel="noopener noreferrer">
                                                <small class="">{{ $url->url }}</small>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="">
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                                </svg>
                                            </x-slot>

                                            <x-slot name="content">
                                                <button class="w-full text-left block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition copy-to-clipboard "
                                                        data-clipboard="{{ $url->url }}" type="button">Copy link
                                                </button>

                                                @if($url->device->device_token)
                                                    <x-dropdown-link class="push-again-link" href="#"
                                                       data-url="{{ $url->url }}"
                                                       data-device="{{ $url->device->id }}"
                                                    >Push again
                                                    </x-dropdown-link>
                                                @endif


                                                <form action="{{ route('urls.destroy', $url) }}" method="post"  class="confirm-delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="w-full text-left block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition"
                                                             type="submit">Delete
                                                    </button>
                                                </form>

                                            </x-slot>
                                        </x-dropdown>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center">
                                    <small class="">
                                        {{ $url->device->name }}
                                    </small>

                                    <small>{{ $url->created_at->diffForHumans() }}</small>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
