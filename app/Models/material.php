<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class material extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'material_name',
        'id_category',
        'material_stock',
        'material_size',
        'material_panjang',
        'material_quantity',
        'material_lebar',
        'material_price',
        'material_unit',
        'p_default',
        'l_default',
        'q_default', 
    ];
 
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id');
    }

}
