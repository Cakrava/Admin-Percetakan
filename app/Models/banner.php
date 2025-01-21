<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class banner extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'first_message',
        'second_message',
        'third_message',
        'image',
    
    ];
}
