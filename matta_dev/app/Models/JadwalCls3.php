<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalCls3 extends Model
{
    use HasFactory;
    protected $table='mtr_tbl_jadwal_cls3';

    public function _nik(){
        return $this->belongsTo('App\Models\Karyawan', 'nik', 'nik');
    }

    public function _shift(){
        return $this->belongsTo('App\Models\JadwalCls3Shift', 'id_shift', 'id');
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
