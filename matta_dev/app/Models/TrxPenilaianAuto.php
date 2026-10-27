<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxPenilaianAuto extends Model
{
    use HasFactory;
    protected $table='trx_tbl_penilaian_auto';

    public function _karyawan(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_karyawan', 'nik');
    }

    public function _penilai1(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_penilai_1', 'nik');
    }

    public function _penilai2(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_penilai_2', 'nik');
    }

    public function _penilai3(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_penilai_3', 'nik');
    }

    public function _tipe(){
        return $this->belongsTo('App\Models\MtrPenilaianTipe', 'id_tipe', 'id');
    }

    public function _status(){
        return $this->belongsTo('App\Models\MtrPenilaianStatus', 'id_status', 'id');
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
