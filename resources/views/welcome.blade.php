@extends('components.welcome.layout')

@section('content')
    @include('components.welcome.header')
    @include('components.welcome.hero')
    @include('components.welcome.ai-assistant')
    @include('components.welcome.services')
    @include('components.welcome.appointment-management')
    @include('components.welcome.find-doctors')
    @include('components.welcome.feedback')
    @include('components.welcome.footer')
    @include('components.welcome.scripts')
@endsection
