<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxReimburseLampiran extends Model
{
    use HasFactory;
    protected $table='trx_tbl_reimburse_lampiran';

    public function _reimburse(){
        return $this->belongsTo('App\Models\TrxReimburseMaster', 'id_reimburse', 'id');
    }

    public function _id_lampiran(){
        return $this->belongsTo('App\Models\ReimburseLampiran', 'id_lampiran', 'id');
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
