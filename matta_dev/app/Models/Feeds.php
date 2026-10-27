<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feeds extends Model
{
    use HasFactory;
    protected $table='trx_tbl_feeds';

    public function _created_by(){
        return $this->belongsTo('App\Models\Karyawan', 'created_by', 'nik');
    }

    public function _updated_by(){
        return $this->belongsTo('App\Models\Karyawan', 'updated_by', 'nik');
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
