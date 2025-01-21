<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'message_history',
        'balance',
    ];

    // Accessor untuk tanggal
    public function getTanggalAttribute()
    {
        return $this->created_at->timezone('Asia/Jakarta')->format('d-m-Y');
    }

    // Accessor untuk waktu
    public function getWaktuAttribute()
    {
        return $this->created_at->timezone('Asia/Jakarta')->format('H:i:s') . ' WIB';
    }
}