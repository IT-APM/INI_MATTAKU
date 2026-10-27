<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrxKebutuhanMaster extends Model
{
    use HasFactory;
    protected $table = 'trx_tbl_kebutuhan_master';

    public function _divisi_kandidat(){
        return $this->belongsTo('App\Models\Divisi', 'id_divisi_kandidat', 'id');
    }

    public function _dep_kandidat(){
        return $this->belongsTo('App\Models\Departemen', 'id_dep_kandidat', 'id');
    }

    public function _sub_dep_kandidat(){
        return $this->belongsTo('App\Models\SubDepartemen', 'id_sub_dep_kandidat', 'id');
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

    public function _pengaju(){
        return $this->belongsTo('App\Models\Karyawan', 'pengaju', 'nik');
    }

    public function _layer1(){
        return $this->belongsTo('App\Models\Karyawan', 'layer1', 'nik');
    }

    public function _layer2(){
        return $this->belongsTo('App\Models\Karyawan', 'layer2', 'nik');
    }

    public function _layer3(){
        return $this->belongsTo('App\Models\Karyawan', 'layer3', 'nik');
    }

    public function _layer4(){
        return $this->belongsTo('App\Models\Karyawan', 'layer4', 'nik');
    }

    public function _layer5(){
        return $this->belongsTo('App\Models\Karyawan', 'layer5', 'nik');
    }

    public function _pekerjaan(){
        return $this->belongsTo('App\Models\PekerjaanSub', 'id_pekerjaan', 'id');
    }

    public function _level(){
        return $this->belongsTo('App\Models\Level', 'id_level', 'id');
    }

    public function _status_kerja(){
        return $this->belongsTo('App\Models\StatusKerja', 'id_status_kerja', 'id');
    }

    public function _kelamin(){
        return $this->belongsTo('App\Models\Kelamin', 'id_kelamin', 'id');
    }

    public function _area(){
        return $this->belongsTo('App\Models\Propinsi', 'id_area', 'id');
    }

    public function _kota(){
        return $this->belongsTo('App\Models\PropinsiKota', 'id_kota', 'id');
    }

    public function _jenis_permintaan(){
        return $this->belongsTo('App\Models\JenisPermintaan', 'id_jenis_permintaan', 'id');
    }

    public function _jenjang_pendidikan(){
        return $this->belongsTo('App\Models\PendidikanJenjang', 'id_jenjang_pendidikan', 'id');
    }

    public function _jurusan_pendidikan(){
        return $this->belongsTo('App\Models\PendidikanJurusan', 'id_jurusan_pendidikan', 'id');
    }

    public function _pengalaman(){
        return $this->belongsTo('App\Models\Pengalaman', 'id_pengalaman', 'id');
    }

    public function _penambahan(){
        return $this->belongsTo('App\Models\RencanaPenambahan', 'id_penambahan', 'id');
    }

    public function _status_pengajuan(){
        return $this->belongsTo('App\Models\KebutuhanStatus', 'status_pengajuan', 'id');
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
