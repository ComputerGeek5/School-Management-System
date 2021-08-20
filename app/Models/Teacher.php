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
    protected $casts = [
        'course_ids' => 'array'
    ];
}
