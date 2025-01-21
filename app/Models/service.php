<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_services',
        'id_material',
        'id_category',
        'item_quantity',
        'descriptions',
        'price',
        'image',
        'isCustomize',
        'input_type',
        
    ];

    // Menambahkan relasi ke model Material
    
    public function detailTransaction()
    {
        return $this->hasMany(DetailTransaction::class, 'service_id', 'id');
    }
    public function tempDataTransactions()
    {
        return $this->hasMany(TempDataTransaction::class, 'service_id', 'id');
    }
    public function material()
    {
        return $this->belongsTo(Material::class, 'id_material', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category', 'id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    // Menambahkan event deleting
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($service) {
            // Hapus semua record terkait di tabel carts
            $service->cart()->delete();
        });
    }
}