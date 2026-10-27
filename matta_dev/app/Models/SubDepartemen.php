<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDepartemen extends Model
{
    use HasFactory;
    protected $table='mtr_tbl_sub_departemen';

    public function _karyawan(){
        return $this->hasMany('App\Models\Karyawan', 'kode_sub_departemen', 'id');
    }

    public function _departemen(){
        return $this->belongsTo('App\Models\Departemen', 'kode_departemen', 'id');
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
