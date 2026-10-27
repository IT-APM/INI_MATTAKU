<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxRecruitmentLog extends Model
{
    use HasFactory;
    protected $table = 'trx_tbl_recruitment_log';

    public function _identitas(){
        return $this->belongsTo('App\Models\TrxRecruitmentIdentitas', 'id_identitas', 'id');
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
