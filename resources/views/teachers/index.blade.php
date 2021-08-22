@extends("layouts.app")

@section("content")
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Code</th>
            <th scope="col">Name</th>
            <th scope="col">ECTS</th>
            <th scope="col">Type</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($courses as $course)
            @if($course->teacher_id === Auth::user()->id)
                <tr>
                    <th>{{ $course->id }}</th>
                    <td>{{ $course->code }}</td>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->ects }}</td>
                    <td>{{ $course->type }}</td>
                    <td class="pt-2">
                        <div class="row d-flex flex-row">
                            <a href="/teachers/{{ $course->id }}" class="btn btn-primary mr-2">View</a>
                            <a href="/teachers/{{ $course->id }}/edit" class="btn btn-success mr-2">Edit</a>
                            {!! Form::open(["action" => ["App\Http\Controllers\TeachersController@destroy", $course->id], "method" => "POST", "enctype" => "multipart/form-data"]) !!}
                                {{ Form::hidden("_method", "DELETE") }}
                                {{ Form::submit("Delete", ["class" => "btn btn-danger"]) }}
                            {!! Form::close() !!}
                        </div>
                    </td>
                </tr>
                @endif
        @endforeach
        </tbody>
    </table>
@endsection
