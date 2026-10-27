<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MtrPenilaian2 extends Model
{
    use HasFactory;
    protected $table='mtr_tbl_penilaian_2';

    public function _nilai1(){
        return $this->belongsTo('App\Models\MtrPenilaian1', 'id_nilai1', 'id');
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
