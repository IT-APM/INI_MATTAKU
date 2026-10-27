<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxReimburseDetail extends Model
{
    use HasFactory;
    protected $table='trx_tbl_reimburse_detail';

    public function _reimburse(){
        return $this->belongsTo('App\Models\TrxReimburseMaster', 'id_reimburse', 'id');
    }

    public function _id_hubungan(){
        return $this->belongsTo('App\Models\ReimburseHubungan', 'id_hubungan', 'id');
    }

    public function _id_layanan(){
        return $this->belongsTo('App\Models\ReimburseLayanan', 'id_layanan', 'id');
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
