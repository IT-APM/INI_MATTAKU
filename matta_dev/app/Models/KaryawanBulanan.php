<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KaryawanBulanan extends Model
{
    use HasFactory;
    protected $table = 'mtr_tbl_karyawan_bulanan';

    public function _nik(){
        return $this->belongsTo('App\Models\Karyawan', 'nik', 'nik');
    }

    public function _layer1(){
        return $this->belongsTo('App\Models\Karyawan', 'layer1', 'nik');
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
