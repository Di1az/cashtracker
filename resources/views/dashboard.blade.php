@extends('layouts.auth')

@section('tittle')
    Administra tus presupuestos
@endsection

@section('auth-contents')
    @if(session('success'))
        <x-alert :message="session('success')"/>
    @endif
@endsection