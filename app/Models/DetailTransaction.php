<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;


class DetailTransaction extends Model
{
    use HasFactory;

     protected $fillable = [
        'service_id', 'jumlah', 'harga', 'quantity', 'lebar', 'panjang', 'image','group_id','lampiran' ,'id_material'
    ];

    

    public function service(): BelongsTo
    {
        return $this->belongsTo(service::class, 'service_id', 'id');
    }
    
    public function material(): BelongsTo
    {
        return $this->belongsTo(material::class, 'id_material', 'id');
    }
    

}
