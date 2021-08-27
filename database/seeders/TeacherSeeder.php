<?php

namespace Database\Seeders;

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
            "Faculty Of Engineering & Architecture", "", "noimage.jpg"
        );

        $this->seedTeacher(
            12, "Sarah Martin", "sarahmartin@gmail.com", "Assistant Professor",
            "Faculty Of Economy", "", "noimage.jpg"
        );

        $this->seedTeacher(
            13, "William Perez", "williamperez@gmail.com", "Associate Professor",
            "Faculty Of Foreign Languages", "", "noimage.jpg"
        );

        $this->seedTeacher(
            14, "Patricia White", "patriciawhite@gmail.com", "Professor",
            "Faculty Of History and Philology", "", "noimage.jpg"
        );

        $this->seedTeacher(
            15, "Daniel Robinson", "danielrobinson@gmail.com", "Instructor",
            "Faculty Of Law & Social Sciences", "", "noimage.jpg"
        );

        $this->seedTeacher(
            16, "Nancy Allen", "nancyallen@gmail.com", "Assistant Professor",
            "Faculty Of Engineering & Architecture", "", "noimage.jpg"
        );

        $this->seedTeacher(
            17, "Steven Adams", "stevenadams@gmail.com", "Instructor",
            "Faculty Of Economy", "", "noimage.jpg"
        );

        $this->seedTeacher(
            18, "Ashley Baker", "ashleybaker@gmail.com", "Associate Professor",
            "Faculty Of Foreign Languages", "", "noimage.jpg"
        );
    }

    public function seedTeacher(
        $id, $name, $email, $title,
        $faculty, $about, $image
    ): void
    {
        DB::table('teachers')->insert([
            "id" => $id,
            "name" => $name,
            "email" => $email,
            "title" => $title,
            "faculty" => $faculty,
            "about" => $about,
            "image" => $image,
        ]);
    }
}
