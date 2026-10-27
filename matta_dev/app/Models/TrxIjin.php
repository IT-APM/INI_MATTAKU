<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxIjin extends Model
{
    use HasFactory;
    protected $table='trx_tbl_ijin';

    public function _karyawan(){
        return $this->belongsTo('App\Models\Karyawan', 'karyawan', 'nik');
    }

    public function _atasan(){
        return $this->belongsTo('App\Models\Karyawan', 'atasan', 'nik');
    }

    public function _tipe(){
        return $this->belongsTo('App\Models\IjinTipe', 'tipe', 'id');
    }

    public function _status(){
        return $this->belongsTo('App\Models\IjinStatus', 'status', 'id');
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
