@extends('layouts.app')

@section("content")
    <div class="row d-flex flex-row">
        <h1>Choose one of the following roles:</h1>
        <a href="/admins/create" class="btn btn-primary ml-5 mr-3">ADMIN</a>
        <a href="/students/create" class="btn btn-primary mr-3">Student</a>
        <a href="/teachers/create" class="btn btn-primary">Teacher</a>
    </div>
@endsection
