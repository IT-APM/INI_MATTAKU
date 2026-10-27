<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxPenilaian extends Model
{
    use HasFactory;
    protected $table='trx_tbl_penilaian';

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

    public function _perpanjang(){
        return $this->belongsTo('App\Models\MtrPenilaianPerpanjang', 'is_perpanjang', 'id');
    }

    public function _perusahaan(){
        return $this->belongsTo('App\Models\Perusahaan', 'id_perusahaan', 'id');
    }

    public function _divisi(){
        return $this->belongsTo('App\Models\Divisi', 'id_divisi', 'id');
    }

    public function _departemen(){
        return $this->belongsTo('App\Models\Departemen', 'id_departemen', 'id');
    }

    public function _sub_departemen(){
        return $this->belongsTo('App\Models\SubDepartemen', 'id_sub_departemen', 'id');
    }

    public function _level(){
        return $this->belongsTo('App\Models\Level', 'id_level', 'id');
    }

    public function _jabatan(){
        return $this->belongsTo('App\Models\Jabatan', 'id_jabatan', 'id');
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
