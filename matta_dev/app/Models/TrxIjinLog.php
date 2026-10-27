<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxIjinLog extends Model
{
    use HasFactory;
    protected $table='trx_tbl_ijin_log';

    public function _pic(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_pic', 'nik');
    }

    public function _status(){
        return $this->belongsTo('App\Models\IjinStatus', 'id_status', 'id');
    }

    public function _trx(){
        return $this->belongsTo('App\Models\TrxIjin', 'id_trx', 'id');
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
