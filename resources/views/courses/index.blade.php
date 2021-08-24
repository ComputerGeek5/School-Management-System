@extends("layouts.app")

@section("content")
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Code</th>
            <th scope="col">Name</th>
            <th scope="col">Teacher</th>
            <th scope="col">Type</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($courses as $course)
            <tr>
                <th>{{ $course->id }}</th>
                <td>{{ $course->code }}</td>
                <td>{{ $course->name }}</td>
                <td>{{ /*\App\Models\Teacher::find(auth()->user()->id)->name*/ $course->teacher->name }}</td>
                <td>{{ $course->type }}</td>
                <td class="pt-2">
                    <div class="row d-flex flex-row">
                        <a href="/teachers/courses/{{ $course->id }}" class="btn btn-primary mr-2">View</a>
                        <a href="/teachers/courses/{{ $course->id }}/edit" class="btn btn-success mr-2">Edit</a>
                        {!! Form::open(["action" => ["App\Http\Controllers\CoursesController@destroy", $course->id], "method" => "POST", "enctype" => "multipart/form-data"]) !!}
                            {{ Form::hidden("_method", "DELETE") }}
                            {{ Form::submit("Delete", ["class" => "btn btn-danger"]) }}
                        {!! Form::close() !!}
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
