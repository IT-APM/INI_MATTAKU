<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SertifikasiSub extends Model
{
    use HasFactory;
    protected $table = 'mtr_tbl_sertifikasi_sub';

    public function _sertifikasi(){
        return $this->belongsTo('App\Models\Sertifikasi', 'id_sertifikasi', 'id');
    }

    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at']) ->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['updated_at']) ->format('d-m-Y H:i:s');
    }
}
