<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxKebutuhanDetail extends Model
{
    use HasFactory;
    protected $table = 'trx_tbl_kebutuhan_detail';

    public function _kebutuhan(){
        return $this->belongsTo('App\Models\TrxKebutuhanMaster', 'id_kebutuhan', 'id');
    }

    public function _pendidikan(){
        return $this->belongsTo('App\Models\PendidikanJenjang', 'id_pendidikan', 'id');
    }

    public function _posisi(){
        return $this->belongsTo('App\Models\PekerjaanSub', 'id_posisi', 'id');
    }

    public function _divisi(){
        return $this->belongsTo('App\Models\SubDepartemen', 'id_divisi', 'id');
    }

    public function _atasan(){
        return $this->belongsTo('App\Models\Karyawan', 'atasan', 'nik');
    }

    public function _pencatatan_kepegawaian(){
        return $this->belongsTo('App\Models\RencanaPenambahan', 'id_pencatatan_kepegawaian', 'id');
    }

    public function _status_kepegawaian(){
        return $this->belongsTo('App\Models\StatusKerja', 'id_status_kepegawaian', 'id');
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
