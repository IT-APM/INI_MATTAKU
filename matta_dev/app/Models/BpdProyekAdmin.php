<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BpdProyekAdmin extends Model
{
    use HasFactory;
    protected $table='mtr_tbl_bpd_proyek_admin';

    public function _proyek(){
        return $this->belongsTo('App\Models\BpdProyek', 'id_proyek', 'id');
    }

    public function _admin(){
        return $this->belongsTo('App\Models\Karyawan', 'admin', 'nik');
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
