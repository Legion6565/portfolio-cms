@extends('layouts.app')

@section('content')

@include('partials.project-form', [
    'project'     => null,
    'action'      => '/admin/create',
    'heading'     => 'Новый проект',
    'subheading'  => 'Создание нового кейса для портфолио.',
    'submitLabel' => 'Сохранить проект',
])

@endsection
