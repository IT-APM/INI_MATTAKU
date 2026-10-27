<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    // protected $table='trx_tbl_attendance_copy';
    protected $table='trx_tbl_attendance';

    public function _att_nik(){
        return $this->belongsTo('App\Models\Karyawan', 'att_nik', 'nik');
    }

    public function _fp_trx(){
        return $this->belongsTo('App\Models\TrxFingePrint', 'id_fp_trx', 'id');
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
