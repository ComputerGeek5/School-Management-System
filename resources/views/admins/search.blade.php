@extends("layouts.app")

@section("content")
    {!! Form::open(["action" => "App\Http\Controllers\AdminsController@search", "method" => "GET"]) !!}
    <div class="form-group">
        {{ Form::text("search", "", ["class" => "form-control", "placeholder" => "Name"]) }}
    </div>
    {{ Form::submit("Search", ["class" => "btn btn-block btn-primary mb-5"]) }}
    {!! Form::close() !!}

    @if($users->isNotEmpty())
        <h1 class="mb-5">Users</h1>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Role</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td class="pt-2">
                    <div class="row d-flex flex-row">
                        @if($user->role === "ADMIN")
                            <a href="/admins/{{ $user->id }}" class="btn btn-primary mr-2">View</a>
                        @elseif($user->role === "Student")
                            <a href="/students/{{ $user->id }}" class="btn btn-primary mr-2">View</a>
                            {!! Form::open(["action" => ["App\Http\Controllers\StudentsController@destroy", $user->id], "method" => "POST", "enctype" => "multipart/form-data"]) !!}
                            {{ Form::hidden("_method", "DELETE") }}
                            {{ Form::submit("Delete", ["class" => "btn btn-danger"]) }}
                            {!! Form::close() !!}
                        @else
                            <a href="/teachers/{{ $user->id }}" class="btn btn-primary mr-2">View</a>
                            {!! Form::open(["action" => ["App\Http\Controllers\TeachersController@destroy", $user->id], "method" => "POST", "enctype" => "multipart/form-data"]) !!}
                            {{ Form::hidden("_method", "DELETE") }}
                            {{ Form::submit("Delete", ["class" => "btn btn-danger"]) }}
                            {!! Form::close() !!}
                        @endif
                    </div>
                </td>
            </tr>
            @endforeach
    @else
        <div>
            <h1><em>No users found</em></h1>
        </div>
    @endif
@endsection
