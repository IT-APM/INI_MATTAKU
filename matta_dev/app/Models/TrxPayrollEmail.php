<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxPayrollEmail extends Model
{
    use HasFactory;
    protected $table = 'trx_tbl_payroll_email';

    public function _nik(){
        return $this->belongsTo('App\Models\Karyawan', 'nik', 'nik');
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
