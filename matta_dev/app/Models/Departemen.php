<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;
    protected $table='mtr_tbl_departemen';

    public function _karyawan(){
        return $this->hasMany('App\Models\Karyawan', 'kode_departemen', 'id');
    }

    public function _subdepartemen(){
        return $this->hasMany('App\Models\Departemen', 'kode_departemen', 'id');
    }

    public function _divisi(){
        return $this->belongsTo('App\Models\Divisi', 'kode_divisi', 'id');
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
