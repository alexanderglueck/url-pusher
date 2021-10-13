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
                                    <div class="d-flex justify-content-between">
                                        <div class="flex-fill ">
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
                                            <button type="button" class="btn btn-sm" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                                                    <path
                                                        d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <button class="dropdown-item copy-to-clipboard" data-clipboard="{{ $url->url }}" type="button">Copy link</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center">

                                        <small class="">
                                            {{ $url->device->name }}

                                            @if($url->device->device_token)
                                                <a class="push-again-link" href="#" data-url="{{ $url->url }}"
                                                   data-device="{{ $url->device->id }}">
                                                    Push again
                                                </a>
                                            @endif
                                        </small>

                                        <div>
                                            <small>{{ $url->created_at->diffForHumans() }}</small>
                                            <form action="{{ route('urls.destroy', $url) }}" method="post"
                                                  class="confirm-delete d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <small>
                                                    <button class="btn btn-link p-0 m-0"
                                                            style="font-size: 100%;vertical-align: inherit">Delete
                                                    </button>
                                                </small>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endempty
                </div>
            </div>
        </div>
    </div>
@endsection
