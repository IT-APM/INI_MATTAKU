<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxRekomendasiDetail extends Model
{
    use HasFactory;
    protected $table='trx_tbl_rekomendasi_detail';

    public function _rekomendasi(){
        return $this->belongsTo('App\Models\TrxRekomendasiMaster', 'id_trx', 'id');
    }

    public function _pengajuan(){
        return $this->belongsTo('App\Models\RekomendasiPengajuan', 'id_pengajuan', 'id');
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
