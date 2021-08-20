@extends("layouts.app")

@section("content")
    <h1>Add User</h1>

    {!! Form::open(["action" => "App\Http\Controllers\AdminsController@store", "method" => "POST", "enctype" => "multipart/form-data"]) !!}
    <div class="form-group">
        {{ Form::label("name", "Name") }}
        {{ Form::text("name", "", ["class" => "form-control", "placeholder" => "Name"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("role", "Role") }}
        {{ Form::text("role", "", ["class" => "form-control", "placeholder" => "ADMIN/Student/Teacher"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("email", "Email") }}
        {{ Form::email("email", "", ["class" => "form-control", "placeholder" => "Email"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("password", "Password") }}
        {{ Form::password("password", ["class" => "form-control", "placeholder" => "Password"]) }}
    </div>
    {{ Form::submit('Create', ["class" => "btn btn-lg btn-success mt-3"]) }}
    {!! Form::close() !!}
@endsection
