@extends("layouts.app")

@section("content")
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Role</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <th>{{ $user->id }}</th>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role }}</td>
                <td class="pt-2">
                    <div class="row d-flex flex-row justify-content-center">
                        <a href="/admins/{{ $user->id }}" class="btn btn-primary mr-2">View</a>
                        <a href="/admins/{{ $user->id }}/edit" class="btn btn-success mr-2">Edit</a>
                        <a href="#" class="btn btn-danger">Delete</a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
