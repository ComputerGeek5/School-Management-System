<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    // Table name
    public $timestamps = true;

    // Primary Key
    protected $table = "teachers";

    // Timestamps
    protected $primaryKey = "id";

    public function courses() {
        return $this->hasMany(Course::class, "teacher_id");
    }
}
