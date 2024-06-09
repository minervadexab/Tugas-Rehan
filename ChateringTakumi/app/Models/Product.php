<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Product extends Model
{
    use HasFactory;

    public $appends = ['picture_url', 'nama_baru'];

 
    public function paket()
    {
        return $this->belongsTo(Paket::class);
    }

    public function getPictureUrlAttribute()
    {
        return $this->image ? asset('storage/'. $this->image) : null;
    }

    public function getNamaBaruAttribute()
    {
        return strtoupper($this->name);
    }
}