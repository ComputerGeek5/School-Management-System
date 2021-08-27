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
        $this->seedUser(
            "Jack Black", "jackblack@gmail.com",
            "admin@1234", "ADMIN"
        );

        $this->seedUser(
            "Mary Smith", "marysmith@gmail.com",
            "admin@1234", "ADMIN"
        );

        // Students
        $this->seedUser(
            "John Green", "johngreen@gmail.com",
            "student@1234", "Student"
        );

        $this->seedUser(
            "Jennifer Williams", "jenniferwilliams@gmail.com",
            "student@1234", "Student"
        );

        $this->seedUser(
            "Charles Brown", "charlesbrown@gmail.com",
            "student@1234", "Student"
        );

        $this->seedUser(
            "Elizabeth Johnson", "elizabethjohnson@gmail.com",
            "student@1234", "Student"
        );

        $this->seedUser(
            "Douglas Jones", "douglasjones@gmail.com",
            "student@1234", "Student"
        );

        $this->seedUser(
            "Barbara Lee", "barbaralee@gmail.com",
            "student@1234", "Student"
        );

        $this->seedUser(
            "Thomas Moore", "thomasmoore@gmail.com",
            "student@1234", "Student"
        );

        $this->seedUser(
            "Regina Miller", "reginamiller@gmail.com",
            "student@1234", "Student"
        );

        // Teachers
        $this->seedUser(
            "Richard Hendrickson", "richardhendrickson@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->seedUser(
            "Sarah Martin", "sarahmartin@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->seedUser(
            "William Perez", "williamperez@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->seedUser(
            "Patricia White", "patriciawhite@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->seedUser(
            "Daniel Robinson", "danielrobinson@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->seedUser(
            "Nancy Allen", "nancyallen@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->seedUser(
            "Steven Adams", "stevenadams@gmail.com",
            "teacher@1234", "Teacher"
        );

        $this->seedUser(
            "Ashley Baker", "ashleybaker@gmail.com",
            "teacher@1234", "Teacher"
        );
    }

    public function seedUser($name, $email, $password, $role): void
    {
        DB::table('users')->insert([
            "name" => $name,
            "email" => $email,
            "password" => Hash::make($password),
            "role" => $role,
        ]);
    }
}
