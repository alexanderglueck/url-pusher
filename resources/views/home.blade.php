@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if($devices->isEmpty())
                            <p>Please add a device first. If you already added a device make sure you signed in on your
                                phone at least once.</p>
                        @else
                            <form action="{{ route('urls.store') }}" method="post" id="push-form">
                                @csrf
                                <div class="input-group ">
                                    <input type="url" class="form-control @error('url') is-invalid @enderror" id="url"
                                           name="url" maxlength="500" placeholder="https://www.google.com"
                                           aria-label="URL" required>

                                    <select name="device_id" id="device_id" class="form-control" aria-label="Device">
                                        @foreach($devices as $device)
                                            <option value="{{ $device->id }}">{{ $device->name }}</option>
                                        @endforeach
                                    </select>

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Push</button>
                                    </div>
                                </div>

                                @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror

                            </form>
                        @endempty
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">Recent pushes</div>

                    @if($urls->isEmpty())
                        <div class="card-body">
                            <p>You have not pushed anything. Try pushing your first URL.</p>
                        </div>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($urls as $url)
                                <li class="list-group-item">
                                    <span class="d-flex flex-column">
                                         <a class="flex-fill" href="{{ $url->url }}" target="_blank" rel="noopener noreferrer">
                                            {{ $url->title }}
                                        </a>
                                        <a class="text-body" href="{{ $url->url }}" target="_blank" rel="noopener noreferrer">
                                            <small class="">{{ $url->url }}</small>
                                        </a>
                                    </span>

                                    <span class="d-flex">

                                        <small class="flex-fill">
                                            {{ $url->device->name }}

                                            @if($url->device->device_token)
                                                <a class="push-again-link" href="#" data-url="{{ $url->url }}" data-device="{{ $url->device->id }}">
                                                    Push again
                                                </a>
                                            @endif
                                        </small>
                                        <small>{{ $url->created_at->diffForHumans() }}</small>
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endempty
                </div>
            </div>
        </div>
    </div>
@endsection
