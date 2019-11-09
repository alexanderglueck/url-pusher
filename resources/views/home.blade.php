@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if($devices->isEmpty())
                            <p>Please add a device first.</p>
                        @else
                            <form action="{{ route('urls.store') }}" method="post">
                                @csrf
                                <div class="input-group ">
                                    <input type="url" class="form-control" id="url" name="url" maxlength="500" placeholder="https://www.google.com" aria-label="URL" required>

                                    <select name="device_id" id="device_id" class="form-control" aria-label="Device">
                                        @foreach($devices as $device)
                                            <option value="{{ $device->id }}">{{ $device->name }}</option>
                                        @endforeach
                                    </select>

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Push</button>
                                    </div>
                                </div>

                            </form>
                        @endempty
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
