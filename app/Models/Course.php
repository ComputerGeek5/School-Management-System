<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // Table name
    protected $table = "courses";

    // Primary Key
    protected $primaryKey = "id";

    // Timestamps
    public $timestamps = true;
}
