@extends("layouts.app")

@section("content")
    <h1 class="mb-5">Edit Profile</h1>

    {!! Form::open(["action" => ["App\Http\Controllers\StudentsController@update", $student->id], "method" => "POST", "enctype" => "multipart/form-data"]) !!}
    <div class="form-group">
        {{ Form::label("name", "Name") }}
        {{ Form::text("name", $student->name, ["class" => "form-control", "placeholder" => "Name"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("password", "Password") }}
        {{ Form::password("password", ["class" => "form-control", "placeholder" => "Password"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("about", "About Me") }}
        {{ Form::textarea('about', $student->about, ["class" => "form-control", "placeholder" => "About Me"]) }}
    </div>
    <div class="form-group">
        {{ Form::label("graduation_year", "Graduation Year") }}
        {{ Form::selectYear('graduation_year', 2021, 2074, ["class" => "form-control", "value" => $student->graduation_year]) }}
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
            ], ["class" => "form-control", "value" => $student->program])
        }}
    </div>
    <div class="form-group">
        {{ Form::label("image", "Profile Picture") }}
        {{ Form::file("image") }}
    </div>
    {{ Form::hidden("_method", "PUT") }}
    {{ Form::submit('Update', ["class" => "btn btn-lg btn-success mt-3"]) }}
    {!! Form::close() !!}
@endsection
