@extends('layouts.app')

@section('content')

@include('partials.project-form', [
    'project'     => $project,
    'action'      => '/admin/update/' . $project->id,
    'heading'     => 'Редактировать проект',
    'subheading'  => 'Изменение существующего кейса портфолио.',
    'submitLabel' => 'Сохранить изменения',
])

@endsection
