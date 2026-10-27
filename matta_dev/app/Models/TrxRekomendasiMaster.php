<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxRekomendasiMaster extends Model
{
    use HasFactory;
    protected $table='trx_tbl_rekomendasi_master';

    public function _karyawan(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_karyawan', 'nik');
    }

    public function _atasan(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_atasan', 'nik');
    }

    public function _reviewer1(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_reviewer1', 'nik');
    }

    public function _reviewer2(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_reviewer2', 'nik');
    }

    public function _reviewer3(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_reviewer3', 'nik');
    }

    public function _status(){
        return $this->belongsTo('App\Models\RekomendasiStatus', 'status_rekomendasi', 'id');
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
