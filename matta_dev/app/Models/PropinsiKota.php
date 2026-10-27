<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropinsiKota extends Model
{
    use HasFactory;
    protected $table = 'mtr_tbl_propinsi_kota';

    public function _propinsi(){
        return $this->belongsTo('App\Models\Propinsi', 'id_propinsi', 'id');
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
