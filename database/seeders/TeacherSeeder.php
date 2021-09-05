<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedTeacher(
            11, "Richard Hendrickson", "richardhendrickson@gmail.com", "Professor",
            "Faculty Of Engineering & Architecture", "24.jpg"
        );

        $this->seedTeacher(
            12, "Sarah Martin", "sarahmartin@gmail.com", "Assistant Professor",
            "Faculty Of Economy", "26.jpg"
        );

        $this->seedTeacher(
            13, "William Perez", "williamperez@gmail.com", "Associate Professor",
            "Faculty Of Foreign Languages", "28.jpg"
        );

        $this->seedTeacher(
            14, "Patricia White", "patriciawhite@gmail.com", "Professor",
            "Faculty Of History and Philology", "29.jpg"
        );

        $this->seedTeacher(
            15, "Daniel Robinson", "danielrobinson@gmail.com", "Instructor",
            "Faculty Of Law & Social Sciences", "27.jpg"
        );

        $this->seedTeacher(
            16, "Nancy Allen", "nancyallen@gmail.com", "Assistant Professor",
            "Faculty Of Engineering & Architecture", "11.jpg"
        );

        $this->seedTeacher(
            17, "Steven Adams", "stevenadams@gmail.com", "Instructor",
            "Faculty Of Economy", "10.jpg"
        );

        $this->seedTeacher(
            18, "Ashley Baker", "ashleybaker@gmail.com", "Associate Professor",
            "Faculty Of Foreign Languages", "12.jpg"
        );
    }

    public function seedTeacher(
        $id, $name, $email, $title,
        $faculty, $image
    ): void
    {
        DB::table('teachers')->insert([
            "id" => $id,
            "name" => $name,
            "email" => $email,
            "title" => $title,
            "faculty" => $faculty,
            "about" => "",
            "image" => $image,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);
    }
}
