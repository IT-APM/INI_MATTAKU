<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxSakitLog extends Model
{
    use HasFactory;
    protected $table='trx_tbl_sakit_log';

    public function _pic(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_pic', 'nik');
    }

    public function _status(){
        return $this->belongsTo('App\Models\SakitStatus', 'id_status', 'id');
    }

    public function _trx(){
        return $this->belongsTo('App\Models\TrxSakit', 'id_trx', 'id');
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
