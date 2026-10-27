<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxBpdLog extends Model
{
    use HasFactory;
    protected $table='trx_tbl_bpd_log';

    public function _trx(){
        return $this->belongsTo('App\Models\TrxBpdMaster', 'id_trx', 'id');
    }

    public function _status(){
        return $this->belongsTo('App\Models\BpdStatus', 'id_status', 'id');
    }

    public function _pic(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_pic', 'nik');
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
