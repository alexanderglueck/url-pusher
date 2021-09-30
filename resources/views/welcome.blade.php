@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">{{ config('app.name') }}</div>

                    <div class="card-body">
                        <a href="{{ asset('storage/url-pusher.apk' )}}">Android App download</a><br>
                        <a href="https://github.com/alexanderglueck/url-pusher">GitHub</a><br>
                        <a href="https://github.com/alexanderglueck/url-pusher-android">GitHub - Android App</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
