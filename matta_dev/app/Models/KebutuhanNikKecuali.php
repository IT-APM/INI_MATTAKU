<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KebutuhanNikKecuali extends Model
{
    use HasFactory;
    protected $table = 'mtr_tbl_kebutuhan_nik_kecuali';

    public function _karyawan(){
        return $this->belongsTo('App\Models\Karyawan', 'karyawan', 'nik');
    }

    public function _layer1(){
        return $this->belongsTo('App\Models\Karyawan', 'layer1', 'nik');
    }

    public function _layer2(){
        return $this->belongsTo('App\Models\Karyawan', 'layer2', 'nik');
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
