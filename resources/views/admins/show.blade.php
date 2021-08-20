@extends("layouts.app")

@section("content")
    <div>
        <div class="row">
            <div class="col-md-4">
                <img src="#" width="100%" alt="">
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
