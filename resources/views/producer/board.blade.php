@extends('layouts.producer')

@auth(\App\Core\Enum\AuthGuard::webProducer->value)
    @section('app-producer')
        <router-view :producer="{{ json_encode($producer, JSON_THROW_ON_ERROR) }}"></router-view>
    @endsection
@endauth
