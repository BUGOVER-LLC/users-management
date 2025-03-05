@extends('layouts.producer')

@auth
    @section('app-producer')
        <router-view :systems="{{ json_encode($systems, JSON_THROW_ON_ERROR) }}"></router-view>
    @endsection
@endauth
