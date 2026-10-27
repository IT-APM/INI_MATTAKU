<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxNonaktifDetail extends Model
{
    use HasFactory;
    protected $table='trx_tbl_nonaktif_detail';

    public function _trx(){
        return $this->belongsTo('App\Models\NonakifMaster', 'id_trx', 'id');
    }

    public function _kepada(){
        return $this->belongsTo('App\Models\Karyawan', 'kepada', 'nik');
    }

    public function _obyek(){
        return $this->belongsTo('App\Models\NonaktifObyek', 'obyek', 'id');
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
