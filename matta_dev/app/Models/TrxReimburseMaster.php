<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxReimburseMaster extends Model
{
    use HasFactory;
    protected $table='trx_tbl_reimburse_master';

    public function _nik(){
        return $this->belongsTo('App\Models\Karyawan', 'nik_karyawan', 'nik');
    }

    public function _status(){
        return $this->belongsTo('App\Models\ReimburseStatus', 'status_pengajuan', 'id');
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
