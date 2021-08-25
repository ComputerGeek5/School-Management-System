@extends("layouts.app")

@section("content")
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="col d-flex flex-column justify-content-center">
                    <h4>Code: <em>{{ $course->code }}</em></h4>
                    <h4>Name: <em>{{ $course->name }}</em></h4>
                    <h4>Teacher: <em>{{ $course->teacher->name }}</em></h4>
                    <h4>ECTS: <em>{{ $course->ects }}</em></h4>
                </div>
                <div class="row d-flex flex-row">
                    @if($course->teacher_id === Auth::user()->id)
                        <a href="/teachers/courses/{{ $course->id }}/edit" class="btn btn-block btn-success mt-2">Edit</a>
                        {!! Form::open(["action" => ["App\Http\Controllers\CoursesController@destroy", $course->id], "method" => "POST", "enctype" => "multipart/form-data", "class" => "w-100 mt-2"]) !!}
                        {{ Form::hidden("_method", "DELETE") }}
                        {{ Form::submit("Delete", ["class" => "btn btn-block btn-danger"]) }}
                        {!! Form::close() !!}
                    @endif
                </div>
            </div>
            <div class="col-md-8">
                <div class="card text-white bg-primary w-100">
                    <div class="card-body">
                        <h1 class="card-title">Description</h1>
                        <p class="card-text">{{ $course->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
