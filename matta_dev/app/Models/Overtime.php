<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Overtime extends Model
{
    use HasFactory;
    protected $table = 'trx_tbl_overtime';

    public function _ot_maker_by(){
        return $this->belongsTo('App\Models\Karyawan', 'ot_maker_by', 'nik');
    }
    
    public function _ot_approver_by(){
        return $this->belongsTo('App\Models\Karyawan', 'ot_approver_by', 'nik');
    }
    
    public function _ot_type_code(){
        return $this->belongsTo('App\Models\OvertimeType', 'ot_type_code', 'id');
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
