@csrf

<!-- Name -->
<div class="col-span-6 sm:col-span-4">
    <x-label for="name" value="{{ __('Device name') }}" />
    <x-input id="name" class="block mt-1 w-full" placeholder="My phone" type="text" name="name" :value="old('name', $device->name)" required autofocus />
    <x-input-error for="name" class="mt-2" />
</div>
