<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perusahaan extends Model
{
    use HasFactory;
    protected $table = 'mtr_tbl_perusahaan';

    public function _karyawan(){
        return $this->hasMany('App\Models\Karyawan', 'kode_perusahaan', 'id');
    }

    public function _divisi(){
        return $this->hasMany('App\Models\Divisi', 'kode_perusahaan', 'id');
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
