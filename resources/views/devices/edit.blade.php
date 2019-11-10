@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-2">
                    <div class="card-header">Edit device</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form action="{{ route('devices.update', $device) }}" method="post">
                            @method('PUT')
                            @include('devices.partials.edit', $device)

                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Delete device</div>

                    <div class="card-body">
                        <form action="{{ route('devices.destroy', $device) }}" method="post">
                            @method('DELETE')
                            @csrf

                            <p>Delete this phone</p>

                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
