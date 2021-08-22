<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // Table name
    public $timestamps = true;

    // Primary Key
    protected $table = "students";

    // Timestamps
    protected $primaryKey = "id";
}
