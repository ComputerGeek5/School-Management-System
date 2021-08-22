@extends("layouts.app")

@section("content")
    <h1 class="mb-5">Add Course</h1>

    {!! Form::open(["action" => "App\Http\Controllers\TeachersController@store", "method" => "POST"]) !!}
    <div class="form-group">
        {{ Form::label("code", "Code") }}
        {{ Form::text("code", "", ["class" => "form-control", "placeholder" => "Code"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("name", "Name") }}
        {{ Form::text("name", "", ["class" => "form-control", "placeholder" => "Name"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("ects", "ECTS") }}
        {{ Form::number("ects", "", ["class" => "form-control", "placeholder" => "ECTS"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("type", "Type") }}
        {{ Form::text("type", "", ["class" => "form-control", "placeholder" => "Type"]) }}
    </div>
    {{ Form::submit('Create', ["class" => "btn btn-lg btn-success mt-3"]) }}
    {!! Form::close() !!}
@endsection
