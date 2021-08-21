@extends("layouts.app")

@section("content")
    <div>
        <div class="row">
            <div class="col-md-4" style="height: 75vh;">
                <div class="row h-75">
                    <img src="#" class="w-100 h-100" alt="">
                </div>
                <div class="row d-flex flex-row">
                    @if($user->id === Auth::user()->id)
                        <a href="/admins/{{ $user->id }}/edit" class="btn btn-block btn-success mt-2">Edit</a>
                    @endif
                    @if($user->role !== "ADMIN" || ($user->role === "ADMIN" && $user->id === Auth::user()->id))
                        {!! Form::open(["action" => ["App\Http\Controllers\AdminsController@destroy", $user->id], "method" => "POST", "enctype" => "multipart/form-data", "class" => "w-100 mt-2"]) !!}
                            {{ Form::hidden("_method", "DELETE") }}
                            {{ Form::submit("Delete", ["class" => "btn btn-block btn-danger"]) }}
                        {!! Form::close() !!}
                        @endif
                </div>
            </div>
            <div class="col-md-8">
                <h1>{{ $user->name }}</h1>
                <hr class="mb-5">
                <h4><strong>Email:</strong> {{ $user->email }}</h4>
                <h4><strong>Role:</strong> <em>{{ $user->role }}</em></h4>
            </div>
        </div>
    </div>
@endsection
