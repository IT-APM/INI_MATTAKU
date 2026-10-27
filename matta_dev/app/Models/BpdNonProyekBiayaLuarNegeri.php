<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpdNonProyekBiayaLuarNegeri extends Model
{
    use HasFactory;
    protected $table='mtr_tbl_bpd_nonproyek_luar';

    public function _negara(){
        return $this->belongsTo('App\Models\Negara', 'id_negara', 'id');
    }

    public function _biaya(){
        return $this->belongsTo('App\Models\BpdBiaya', 'id_biaya', 'id');
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
