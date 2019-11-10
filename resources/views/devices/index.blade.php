@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Devices</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if($devices->isEmpty())
                            <p><a href="{{ route('devices.create') }}">Add your first device.</a></p>
                        @else
                            <p><a href="{{ route('devices.create') }}">Add a new device.</a></p>
                            <ul>
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
        </div>
    </div>
@endsection
