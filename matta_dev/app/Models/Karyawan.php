<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;
    protected $table='mtr_tbl_karyawan';

    public function _perusahaan(){
        return $this->belongsTo('App\Models\Perusahaan', 'kode_perusahaan', 'id');
    }

    public function _divisi(){
        return $this->belongsTo('App\Models\Divisi', 'kode_divisi', 'id');
    }

    public function _departemen(){
        return $this->belongsTo('App\Models\Departemen', 'kode_departemen', 'id');
    }

    public function _subdepartemen(){
        return $this->belongsTo('App\Models\SubDepartemen', 'kode_sub_departemen', 'id');
    }

    public function _level(){
        return $this->belongsTo('App\Models\Level', 'kode_level', 'id');
    }

    public function _jabatan(){
        return $this->belongsTo('App\Models\Jabatan', 'kode_jabatan', 'id');
    }

    // public function _atasan(){
    //     return $this->hasMany('App\Models\Karyawan', 'layer1', 'nik');
    // }

    public function _layer1(){
        return $this->belongsTo('App\Models\Karyawan', 'layer1', 'nik');
    }

    public function _layer2(){
        return $this->belongsTo('App\Models\Karyawan', 'layer2', 'nik');
    }

    public function _layer3(){
        return $this->belongsTo('App\Models\Karyawan', 'layer3', 'nik');
    }

    public function _wg(){
        return $this->belongsTo('App\Models\WorkingGroup', 'wg_id', 'id');
    }

    public function _status(){
        return $this->belongsTo('App\Models\KaryawanStatus', 'kode_status', 'id');
    }

    public function _agama(){
        return $this->belongsTo('App\Models\Agama', 'kode_agama', 'id');
    }

    public function _kelamin(){
        return $this->belongsTo('App\Models\Kelamin', 'kode_kelamin', 'id');
    }

    public function _warga(){
        return $this->belongsTo('App\Models\Warga', 'kode_warga', 'id');
    }

    public function _kawin(){
        return $this->belongsTo('App\Models\Kawin', 'kode_kawin', 'id');
    }

    public function _findim_project(){
        return $this->belongsTo('App\Models\MtrFindimProject', 'findim_project', 'id');
    }

    public function _findim_divisi(){
        return $this->belongsTo('App\Models\MtrFindimDivisi', 'findim_divisi', 'id');
    }

    public function _findim_departemen(){
        return $this->belongsTo('App\Models\MtrFindimDepartemen', 'findim_departemen', 'id');
    }

    public function _findim_subdepartemen(){
        return $this->belongsTo('App\Models\MtrFindimSubdepartemen', 'findim_subdepartemen', 'id');
    }

    public function _grade(){
        return $this->belongsTo('App\Models\KaryawanGrade', 'id_grade', 'id');
    }

    public function getCreatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['created_at']) ->format('d-m-Y H:i:s');
    }

    public function getUpdatedAtAttribute()
    {
        return \Carbon\Carbon::parse($this->attributes['updated_at']) ->format('d-m-Y H:i:s');
    }

    public function getNikNkp()
    {
        return self::where(['kode_perusahaan'=>5])->pluck('nik')->toArray();
    }
}
