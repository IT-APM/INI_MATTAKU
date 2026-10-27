<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    use HasFactory;
    protected $table='mtr_tbl_divisi';

    public function _karyawan(){
        return $this->hasMany('App\Models\Karyawan', 'kode_divisi', 'id');
    }

    public function _departemen(){
        return $this->hasMany('App\Models\Departemen', 'kode_divisi', 'id');
    }

    public function _perusahaan(){
        return $this->belongsTo('App\Models\Perusahaan', 'kode_perusahaan', 'id');
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
