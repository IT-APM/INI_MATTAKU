<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxBpdDetailInfo extends Model
{
    use HasFactory;
    protected $table='trx_tbl_bpd_detail_info';

    public function _trx(){
        return $this->belongsTo('App\Models\TrxBpdMaster', 'id_trx', 'id');
    }

    public function _tujuan1_dalam(){
        return $this->belongsTo('App\Models\Propinsi', 'tujuan1', 'id');
    }

    public function _tujuan2_dalam(){
        return $this->belongsTo('App\Models\PropinsiKota', 'tujuan2', 'id');
    }

    public function _tujuan1_luar(){
        return $this->belongsTo('App\Models\Negara', 'tujuan1', 'id');
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
