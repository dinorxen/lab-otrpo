@extends('layouts.app')

@section('title', $country->name)

@section('content')
    <h1 class="mb-4">{{ $country->name }}</h1>

    @include('countries._details')

    <a href="{{ route('countries.index') }}" class="btn btn-secondary mt-3">Назад к списку</a>
@endsection
