<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxPelatihan extends Model
{
    use HasFactory;
    protected $table='trx_tbl_pelatihan';

    public function _pengaju(){
        return $this->belongsTo('App\Models\Karyawan', 'pengaju', 'nik');
    }

    public function _atasan(){
        return $this->belongsTo('App\Models\Karyawan', 'atasan_pengaju', 'nik');
    }

    public function _reviewer1(){
        return $this->belongsTo('App\Models\Karyawan', 'reviewer1', 'nik');
    }

    public function _reviewer2(){
        return $this->belongsTo('App\Models\Karyawan', 'reviewer2', 'nik');
    }

    public function _anggaran(){
        return $this->belongsTo('App\Models\PelatihanAnggaran', 'anggaran', 'id');
    }

    public function _status(){
        return $this->belongsTo('App\Models\PelatihanStatus', 'status_pengajuan', 'id');
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
