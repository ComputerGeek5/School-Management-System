@extends("layouts.app")

@section("content")
        <div class="row">
            <div class="col-md-4" style="height: 75vh;">
                <div class="row h-75 mb-3">
                    <img src="/storage/images/{{ $teacher->image }}" class="w-100 h-100" alt="{{ $teacher->image }}" style="vertical-align: middle; border-radius: 50%;">
                </div>
                <div class="row d-flex flex-row">
                    @if($teacher->id === Auth::user()->id)
                        <a href="/teachers/{{ $teacher->id }}/edit" class="btn btn-block btn-success mt-2">Edit</a>
                    @endif
                    @if($teacher->id === Auth::user()->id || Auth::user()->role === "ADMIN")
                        {!! Form::open(["action" => ["App\Http\Controllers\TeachersController@destroy", $teacher->id], "method" => "POST", "enctype" => "multipart/form-data", "class" => "w-100 mt-2"]) !!}
                        {{ Form::hidden("_method", "DELETE") }}
                        {{ Form::submit("Delete", ["class" => "btn btn-block btn-danger"]) }}
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
            <div class="col-md-8">
                <h1>{{ $teacher->name }}</h1>
                <hr class="mb-5">
                <h4>Email: <em>{{ $teacher->email }}</em></h4>
                <h4>Title: <em>{{ $teacher->title }}</em></h4>
                <h4>Faculty: <em>{{ $teacher->faculty }}</em></h4>
                <h4>About Me:</h4>
                <div class="jumbotron jumbotron-fluid">
                    <div class="container">
                        <p class="lead ml-5">{{ $teacher->about }}</p>
                    </div>
                </div>
            </div>
        </div>
        @if(Auth::user()->id !== $teacher->id)
            <h4 class="mb-2">Courses: </h4>
            <div class="card width-100">
                <ul class="list-group list-group-flush">
                    @foreach($teacher->courses as $course)
                        <li class="list-group-item w-100 text-center">
                            <a href="/teachers/courses/{{ $course->id }}" class="text-center">{{ $course->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
@endsection
