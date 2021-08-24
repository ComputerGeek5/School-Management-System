@extends("layouts.app")

@section("content")
    <h1 class="mb-5">Take Courses</h1>
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
                        @if(!in_array($course->id, $courses_ids))
                            <a href="/students/enroll/{{ $course->id }}" class="btn btn-success">Enroll</a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
