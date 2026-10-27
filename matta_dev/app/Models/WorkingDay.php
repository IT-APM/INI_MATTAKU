<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingDay extends Model
{
    use HasFactory;
    protected $table = 'trx_tbl_working_day';

    public function _nik(){
        return $this->belongsTo('App\Models\Karyawan', 'nik', 'nik');
    }

    public function _wgId(){
        return $this->belongsTo('App\Models\WorkingGroup', 'wg_id', 'id');
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
