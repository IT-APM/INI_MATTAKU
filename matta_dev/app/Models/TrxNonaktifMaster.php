<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxNonaktifMaster extends Model
{
    use HasFactory;
    protected $table='trx_tbl_nonaktif_master';

    public function _pengaju(){
        return $this->belongsTo('App\Models\Karyawan', 'pengaju', 'nik');
    }

    public function _atasan(){
        return $this->belongsTo('App\Models\Karyawan', 'atasan', 'nik');
    }

    public function _alasan(){
        return $this->belongsTo('App\Models\NonaktifAlasan', 'alasan', 'id');
    }

    public function _status(){
        return $this->belongsTo('App\Models\NonaktifStatus', 'status', 'id');
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
