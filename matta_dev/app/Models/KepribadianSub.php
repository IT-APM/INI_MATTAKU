<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KepribadianSub extends Model
{
    use HasFactory;
    protected $table = 'mtr_tbl_kepribadian_sub';

    public function _kepribadian(){
        return $this->belongsTo('App\Models\Kepribadian', 'id_kepribadian', 'id');
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
