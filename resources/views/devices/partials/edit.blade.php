@csrf

<div class="form-group">
    <label for="name">Device name</label>
    <input type="text"
           class="form-control @error('name') is-invalid @enderror"
           id="name"
           placeholder="My phone"
           name="name"
           value="{{ old('name', $device->name) }}"
           required
           autofocus
    >
    @error('name')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>

