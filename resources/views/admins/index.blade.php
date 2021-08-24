@extends("layouts.app")

@section("content")
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
            @if($user->id !== Auth::user()->id)
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
            @endif
        @endforeach
        </tbody>
    </table>
@endsection
