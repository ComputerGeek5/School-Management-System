<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // Table name
    protected $table = "students";

    // Primary Key
    protected $primaryKey = "id";

    // Timestamps
    public $timestamps = true;

    protected $casts = [
        'course_ids' => 'array'
    ];
}
