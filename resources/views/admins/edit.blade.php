@extends("layouts.app")

@section("content")
    <h1 class="mb-5">Edit Profile</h1>

    {!! Form::open(["action" => ["App\Http\Controllers\UsersController@update", $user->id], "method" => "POST", "enctype" => "multipart/form-data"]) !!}
    <div class="form-group">
        {{ Form::label("name", "Name") }}
        {{ Form::text("name", $user->name, ["class" => "form-control", "placeholder" => "Name"]) }}
    </div>
    {{ Form::hidden("_method", "PUT") }}
    {{ Form::submit('Update', ["class" => "btn btn-lg btn-success mt-3"]) }}
    {!! Form::close() !!}
@endsection
