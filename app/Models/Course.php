<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    // Table name
    public $timestamps = true;

    // Primary Key
    protected $table = "courses";

    // Timestamps
    protected $primaryKey = "id";

    public function teacher() {
        return $this->belongsTo(Teacher::class);
    }
}
