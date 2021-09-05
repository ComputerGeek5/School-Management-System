<?php

namespace Database\Seeders;

use Carbon\Carbon;
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
            3, "John Green","johngreen@gmail.com", "Software Engineering",
            "2027", "7.jpg"
        );

        $this->seedStudent(
            4, "Jennifer Williams","jenniferwilliams@gmail.com", "Economics",
            "2025", "9.jpg"
        );

        $this->seedStudent(
            5, "Charles Brown","charlesbrown@gmail.com", "Banking & Finance",
            "2029", "4.jpg"
        );

        $this->seedStudent(
            6, "Elizabeth Johnson","elizabethjohnson@gmail.com", "Law",
            "2027", "6.jpg"
        );

        $this->seedStudent(
            7, "Douglas Jones","douglasjones@gmail.com", "Computer Engineering",
            "2023",  "8.jpg"
        );

        $this->seedStudent(
            8, "Barbara Lee","barbaralee@gmail.com", "Psychology",
            "2030", "13.jpg"
        );

        $this->seedStudent(
            9, "Thomas Moore","thomasmoore@gmail.com", "History",
            "2024", "15.jpg"
        );

        $this->seedStudent(
            10, "Regina Miller","reginamiller@gmail.com", "Italian Language",
            "2032", "14.jpg"
        );
    }

    public function seedStudent(
        $id, $name, $email, $program,
        $graduation_year, $image
    ): void
    {
        DB::table('students')->insert([
            "id" => $id,
            "name" => $name,
            "courses" => "[]",
            "email" => $email,
            "program" => $program,
            "graduation_year" => $graduation_year,
            "about" => "",
            "image" => $image,
            "created_at" => Carbon::now(),
            "updated_at" => Carbon::now(),
        ]);
    }
}
