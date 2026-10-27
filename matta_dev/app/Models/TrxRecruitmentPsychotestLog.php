<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxRecruitmentPsychotestLog extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'trx_tbl_recruitment_psychotest_log';

    public function _identitas(){
        return $this->belongsTo('App\Models\TrxRecruitmentIdentitas', 'id_identitas', 'id');
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
