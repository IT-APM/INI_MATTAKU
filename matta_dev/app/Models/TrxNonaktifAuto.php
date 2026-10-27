<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxNonaktifAuto extends Model
{
    use HasFactory;
    protected $table='trx_tbl_nonaktif_auto';

    public function _karyawan(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_karyawan', 'nik');
    }

    public function _atasan(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_atasan', 'nik');
    }

    public function _id(){
        return $this->belongsTo('App\Models\TrxNonaktifMaster', 'id_trx', 'id');
    }

    public function _status(){
        return $this->belongsTo('App\Models\NonaktifStatus', 'id_status', 'id');
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
