<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxBpdDetailBiaya extends Model
{
    use HasFactory;
    protected $table='trx_tbl_bpd_detail_biaya';

    public function _trx(){
        return $this->belongsTo('App\Models\TrxBpdMaster', 'id_trx', 'id');
    }

    public function _info(){
        return $this->belongsTo('App\Models\TrxBpdDetailInfo', 'id_info', 'id');
    }

    public function _coa(){
        return $this->belongsTo('App\Models\BpdCoa', 'id_coa', 'id');
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
