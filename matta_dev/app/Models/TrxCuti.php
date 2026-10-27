<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxCuti extends Model
{
    use HasFactory;
    protected $table='trx_tbl_cuti';

    public function _tipe(){
        return $this->belongsTo('App\Models\CutiTipe', 'tipe_cuti', 'id');
    }

    public function _karyawan(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_karyawan', 'nik');
    }

    public function _atasan(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_atasan', 'nik');
    }

    public function _status(){
        return $this->belongsTo('App\Models\CutiStatus', 'status_cuti', 'id');
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
