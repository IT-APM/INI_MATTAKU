<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxPenilaianSkor extends Model
{
    use HasFactory;
    protected $table='trx_tbl_penilaian_skor';

    public function _penilaian3(){
        return $this->belongsTo('App\Models\MtrPenilaian3', 'id_nilai3', 'id');
    }

    public function _trx(){
        return $this->belongsTo('App\Models\TrxPenilaian', 'id_trx', 'id');
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
