<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class transaction extends Model
{
    use HasFactory;  protected $table = 'transactions'; // Nama tabel jika berbeda dengan nama model
    protected $primaryKey = 'id'; // Jika 'id' adalah primary key
    public $incrementing = false; // Nonaktifkan auto-increment
    protected $keyType = 'string'; 
    protected $fillable = [
        // 'id'=> 'id_transaction',
        'id',
        'id_customer',
        'id_payment',
        'group_id',
        'total_price',
        'status',
        'snap_token',
    ];

    

    // Relasi ke Customer
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id');
    }

    // Relasi ke Payment
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'id_payment', 'id');
    }

    // Relasi ke DetailTransaction
    public function detailTransactions(): HasMany
    {
        return $this->hasMany(DetailTransaction::class, 'group_id', 'group_id');
    }

    // Relasi ke Service melalui DetailTransaction
    public function services()
    {
        return $this->hasManyThrough(
            Service::class,
            DetailTransaction::class,
            'group_id', // Foreign key pada DetailTransaction
            'id', // Foreign key pada Service
            'group_id', // Local key pada Transaction
            'id_service' // Local key pada DetailTransaction
        );
    }

    // Relasi ke Material melalui DetailTransaction
    public function materials()
    {
        return $this->hasManyThrough(
            Material::class,
            DetailTransaction::class,
            'group_id', // Foreign key pada DetailTransaction
            'id', // Foreign key pada Material
            'group_id', // Local key pada Transaction
            'id_material' // Local key pada DetailTransaction
        );
    }
}