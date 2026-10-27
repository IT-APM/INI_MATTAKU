<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxReimburseLog extends Model
{
    use HasFactory;
    protected $table='trx_tbl_reimburse_log';

    public function _reimburse(){
        return $this->belongsTo('App\Models\TrxReimburseMaster', 'id_reimburse', 'id');
    }

    public function _id_status(){
        return $this->belongsTo('App\Models\ReimburseStatus', 'id_status', 'id');
    }

    public function _nik(){
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
