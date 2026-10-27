<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxBpdMaster extends Model
{
    use HasFactory;
    protected $table='trx_tbl_bpd_master';

    public function _project(){
        return $this->belongsTo('App\Models\BpdProyek', 'project_id', 'id');
    }

    public function _kelompok(){
        return $this->belongsTo('App\Models\BpdKelompok', 'kelompok_id', 'id');
    }


    public function _status(){
        return $this->belongsTo('App\Models\BpdStatus', 'status_id', 'id');
    }

    public function _pengaju(){
        return $this->belongsTo('App\Models\Karyawan', 'pengaju', 'nik');
    }

    public function _atasan(){
        return $this->belongsTo('App\Models\Karyawan', 'atasan', 'nik');
    }

    public function _admin(){
        return $this->belongsTo('App\Models\Karyawan', 'admin', 'nik');
    }

    public function _koordinator(){
        return $this->belongsTo('App\Models\Karyawan', 'koordinator', 'nik');
    }

    public function _layer1(){
        return $this->belongsTo('App\Models\Karyawan', 'layer1', 'nik');
    }

    public function _layer2(){
        return $this->belongsTo('App\Models\Karyawan', 'layer2', 'nik');
    }

    public function _hco(){
        return $this->belongsTo('App\Models\Karyawan', 'hco', 'nik');
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
