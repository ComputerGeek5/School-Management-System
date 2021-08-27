<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedCourse(
            "Object-Oriented Programming", 11, "CEN107", 7,
            "Compulsory", ""
        );

        $this->seedCourse(
            "Web Programming", 11, "WEB304", 5,
            "Elective", ""
        );

        $this->seedCourse(
            "Turkish", 18, "TUR607", 4,
            "Elective", ""
        );

        $this->seedCourse(
            "Economy", 12, "ECO501", 6,
            "Compulsory", ""
        );

        $this->seedCourse(
            "Ancient History", 14, "ANC321", 7,
            "Compulsory", ""
        );

        $this->seedCourse(
            "Geomorphology", 14, "GEO780", 5,
            "Elective", ""
        );

        $this->seedCourse(
            "Data Structures & Algorithms", 18, "DAS444", 7,
            "Compulsory", ""
        );

        $this->seedCourse(
            "Geopolitics", 15, "GPL866", 5,
            "Compulsory", ""
        );
    }

    public function seedCourse(
        $name, $teacher_id, $code, $ects,
        $type, $description
    ): void
    {
        DB::table('courses')->insert([
            "name" => $name,
            "teacher_id" => $teacher_id,
            "code" => $code,
            "ects" => $ects,
            "type" => $type,
            "description" => $description,
        ]);
    }
}
