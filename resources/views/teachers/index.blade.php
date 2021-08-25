@extends("layouts.app")

@section("content")
    <h1 class="mb-5">Teachers</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Title</th>
            <th scope="col">Faculty</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td>{{ $teacher->title }}</td>
                    <td>{{ $teacher->faculty }}</td>
                    <td class="pt-2">
                        <div class="row d-flex flex-row">
                            <a href="/teachers/{{ $teacher->id }}" class="btn btn-primary mr-2">View</a>
                        </div>
                    </td>
                </tr>
        @endforeach
        </tbody>
    </table>
@endsection
