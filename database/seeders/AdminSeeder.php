<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedAdmin(
            1, "Jack Black", "jackblack@gmail.com", "noimage.jpg"
        );

        $this->seedAdmin(
            2, "Mary Smith", "marysmith@gmail.com", "noimage.jpg"
        );
    }

    public function seedAdmin($id, $name, $email, $image): void
    {
        DB::table('admins')->insert([
            "id" => $id,
            "name" => $name,
            "email" => $email,
            "image" => $image,
        ]);
    }
}
