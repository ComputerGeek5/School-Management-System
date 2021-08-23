<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    // Table name
    public $timestamps = true;

    // Primary Key
    protected $table = "admins";

    // Timestamps
    protected $primaryKey = "id";
}
