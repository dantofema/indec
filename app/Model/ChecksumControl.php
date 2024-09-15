<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChecksumControl extends Model
{
    use HasFactory;
    protected $primaryKey = 'archivo_id';
    public function archivo()
    {
        return $this->belongsTo(Archivo::class);
    }
}
