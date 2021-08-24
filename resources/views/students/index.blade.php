@extends("layouts.app")

@section("content")
    <h1 class="mb-5">Students</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($students as $student)
            <tr>
                <td>{{ $student->name }}</td>
                <td class="pt-2">
                    <div class="row d-flex flex-row">
                        <a href="/students/{{ $student->id }}" class="btn btn-primary mr-2">View</a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
