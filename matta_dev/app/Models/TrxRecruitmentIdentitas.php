<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxRecruitmentIdentitas extends Model
{
    use HasFactory;
    protected $table = 'trx_tbl_recruitment_identitas';

    public function _kebutuhan(){
        return $this->belongsTo('App\Models\TrxKebutuhanMaster', 'id_kebutuhan', 'id');
    }

    public function _recruitment(){
        return $this->belongsTo('App\Models\TrxRecruitment', 'id_recruitment', 'id');
    }

    public function _status(){
        return $this->belongsTo('App\Models\MtrRecruitmentStatus', 'id_status', 'id');
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
