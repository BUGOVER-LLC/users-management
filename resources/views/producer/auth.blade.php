@extends('layouts.producer')

@guest
    @section('app-producer')
        <router-view
            code="{{ $code }}"
            email="{{ $email }}"
            step="{{ $step }}"
            password-accept="{{ $passwordConfirm }}"
        >
        </router-view>
    @endsection
@endguest
