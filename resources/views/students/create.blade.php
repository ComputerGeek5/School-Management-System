@extends("layouts.app")

@section("content")
    <h1 class="mb-5">Add Student</h1>

    {!! Form::open(["action" => "App\Http\Controllers\StudentsController@store", "method" => "POST", "enctype" => "multipart/form-data"]) !!}
    <div class="form-group">
        {{ Form::label("name", "Name") }}
        {{ Form::text("name", "", ["class" => "form-control", "placeholder" => "Name"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("email", "Email") }}
        {{ Form::email("email", "", ["class" => "form-control", "placeholder" => "Email"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("graduation_year", "Graduation Year") }}
        {{ Form::selectYear('graduation_year', 2021, 2074, ["class" => "form-control"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("program", "Program") }}
        {{ Form::select("program",[
                "Faculty Of Engineering & Architecture" => [
                        "Computer Engineering" => "CEN",
                        "Electronic & Communications Engineering" => "ECE",
                        "Software Engineering" => "SEN",
                        "Civil Engineering" => "CIN",
                        "Architecture" => "ARCH"
                    ],
                "Faculty Of Economy" => [
                        "Economics" => "ECO",
                        "Business Administration" => "BUA",
                        "Business Informatics" => "BINF",
                        "Banking & Finance" => "BAF",
                        "International Marketing & Logistics Management" => "MAL"
                    ],
                "Faculty Of Law & Social Sciences" => [
                        "Political Science and International Relations" => "PSR",
                        "Law" => "LAW"
                    ],
            ], ["class" => "form-control"])
        }}
    </div>
    <div class="form-group">
        {{ Form::label("image", "Profile Picture") }}
        {{ Form::file("image") }}
    </div>
    {{ Form::submit('Create', ["class" => "btn btn-lg btn-success mt-3"]) }}
    {!! Form::close() !!}
@endsection
