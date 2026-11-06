@extends('layouts.app')

@section('title', 'Добавить страну')

@section('content')
    <h1 class="mb-4">Добавить страну</h1>

    <form action="{{ route('countries.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
        @include('countries._form')

        <button type="submit" class="btn btn-primary">Сохранить</button>
        <a href="{{ route('countries.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
@endsection
