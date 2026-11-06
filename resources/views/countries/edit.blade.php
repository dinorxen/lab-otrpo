@extends('layouts.app')

@section('title', 'Изменить страну')

@section('content')
    <h1 class="mb-4">Изменить страну</h1>

    <form action="{{ route('countries.update', $country) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @method('PUT')
        @include('countries._form')

        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('countries.index') }}" class="btn btn-secondary">Отмена</a>
    </form>

    @include('countries._historical-facts', ['manageFacts' => true])
@endsection
