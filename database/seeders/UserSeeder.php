<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admins
        $this->extracted(
            "Jack Black", "jackblack@gmail.com",
            "admin@1234", "ADMIN"
        );

        $this->extracted(
            "Mary Smith", "marysmith@gmail.com",
            "admin@1234", "ADMIN"
        );

        // Students
        $this->extracted(
            "John Green", "johngreen@gmail.com",
            "student@1234", "Student"
        );

        $this->extracted(
            "Jennifer Williams", "jenniferwilliams@gmail.com",
            "student@1234", "Student"
        );

        $this->extracted(
            "Charles Brown", "charlesbrown@gmail.com",
            "student@1234", "Student"
        );

        $this->extracted(
            "Elizabeth Johnson", "elizabethjohnson@gmail.com",
            "student@1234", "Student"
        );

        $this->extracted(
            "Douglas Jones", "douglasjones@gmail.com",
            "student@1234", "Student"
        );

        $this->extracted(
            "Barbara Lee", "barbaralee@gmail.com",
            "student@1234", "Student"
        );

        $this->extracted(
            "Thomas Moore", "thomasmoore@gmail.com",
            "student@1234", "Student"
        );

        $this->extracted(
            "Regina Miller", "reginamiller@gmail.com",
            "student@1234", "Student"
        );

        // Teachers
        $this->extracted(
            "Richard Hendrickson", "richardhendrickson@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->extracted(
            "Sarah Martin", "sarahmartin@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->extracted(
            "William Perez", "williamperez@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->extracted(
            "Patricia White", "patriciawhite@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->extracted(
            "Daniel Robinson", "danielrobinson@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->extracted(
            "Nancy Allen", "nancyallen@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->extracted(
            "Steven Adams", "stevenadams@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->extracted(
            "Ashley Baker", "ashleybaker@gmail.com",
            "teacher@1234", "Teacher"
        );
    }

    public function extracted($name, $email, $password, $role): void
    {
        DB::table('users')->insert([
            "name" => $name,
            "email" => $email,
            "password" => Hash::make($password),
            "role" => $role,
        ]);
    }
}
