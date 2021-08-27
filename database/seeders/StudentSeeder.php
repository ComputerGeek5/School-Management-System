<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedStudent(
            3, "John Green", array(),"johngreen@gmail.com", "Software Engineering",
            "2027", "", "noimage.jpg"
        );

        $this->seedStudent(
            4, "Jennifer Williams", array(),"jenniferwilliams@gmail.com", "Economics",
            "2025", "", "noimage.jpg"
        );

        $this->seedStudent(
            5, "Charles Brown", array(),"charlesbrown@gmail.com", "Banking & Finance",
            "2029", "", "noimage.jpg"
        );

        $this->seedStudent(
            6, "Elizabeth Johnson", array(),"elizabethjohnson@gmail.com", "Law",
            "2027", "", "noimage.jpg"
        );

        $this->seedStudent(
            7, "Douglas Jones", array(),"douglasjones@gmail.com", "Computer Engineering",
            "2023", "", "noimage.jpg"
        );

        $this->seedStudent(
            8, "Barbara Lee", array(),"barbaralee@gmail.com", "Psychology",
            "2030", "", "noimage.jpg"
        );

        $this->seedStudent(
            9, "Thomas Moore", array(),"thomasmoore@gmail.com", "History",
            "2024", "", "noimage.jpg"
        );

        $this->seedStudent(
            10, "Regina Miller", array(),"reginamiller@gmail.com", "Italian Language",
            "2032", "", "noimage.jpg"
        );
    }

    public function seedStudent(
        $id, $name, $courses, $email,
        $program, $graduation_year, $about, $image
    ): void
    {
        DB::table('users')->insert([
            "id" => $id,
            "name" => $name,
            "courses" => $courses,
            "email" => $email,
            "program" => $program,
            "graduation_year" => $graduation_year,
            "about" => $about,
            "image" => $image,
        ]);
    }
}
