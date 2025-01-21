<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempDataTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'service_id', 'jumlah', 'harga', 'quantity', 'lebar', 'panjang', 'image','group_id','lampiran' ,'id_material'
    ];



    // Definisikan relationship ke model Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
