<?php

namespace App\Exports;

use App\Models\TrxRecruitmentPsychotest;
use App\Models\TrxRecruitmentPsychotestLog;
use App\Models\TrxRecruitmentIdentitas;
use App\Models\TrxRecruitmentTiu5;
use App\Models\MtrRecruitmentSoalTiu5Norma;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
// use Maatwebsite\Excel\Facades\Excel;

class TrxRecruitmentPsychotestExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithCustomStartCell, WithDrawings
{
    private $id;
    public function __construct(Request $request, $id) 
    {
        // $id berisikan array
        // $id[0] = id_identitas
        // $id[1] = jenis
        // $id[2] = urutan
        $this->id = $id;
    }

    public function startCell(): string
    {
        $id = $this->id;
        // tipe 6
        if($id[1] == 6){
            return 'A11';
        }
        // selain tipe 6
        else{
            return 'A7';
        }
    }

    public function styles(Worksheet $sheet)
    {
        $id = $this->id;
        $trx = TrxRecruitmentIdentitas::find($id[0]);
        $sheet->setCellValue('A1', '#ID');
        $sheet->setCellValue('A2', 'Nama/email');
        $sheet->setCellValue('A3', 'Jenis kelamin (L/P)');
        $sheet->setCellValue('A4', 'Pendidikan');
        $sheet->setCellValue('A5', 'Usia');
        $sheet->setCellValue('B1', ': '.$trx->id_kebutuhan.'/'.$trx->id);
        $sheet->setCellValue('B2', ': '.$trx->nama.'/'.$trx->email);
        $sheet->setCellValue('B3', ': '.$trx->kelamin);
        $sheet->setCellValue('B4', ': '.$trx->pendidikan);
        $sheet->setCellValue('B5', ': '.$trx->usia);
        // tipe 1
        if($id[1] == 1){
            // 
        }
        // tipe 2
        elseif($id[1] == 2){
            $awal = 8;
            $akhir = 9;
            for($x = 0; $x < 90; $x++) {
                $sheet->mergeCells('C'.$awal+($x*2).':C'.$akhir+($x*2));
                $sheet->mergeCells('A'.$awal+($x*2).':A'.$akhir+($x*2));
                $range = 'C'.$awal+($x*2).':C'.$akhir+($x*2);
                $range2 = 'A'.$awal+($x*2).':A'.$akhir+($x*2);
                $style = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ];
                $sheet->getStyle($range)->applyFromArray($style);
                $sheet->getStyle($range2)->applyFromArray($style);
            }
        }
        // tipe 3
        elseif($id[1] == 3){
            // 
        }
        // tipe 6
        elseif($id[1] == 6){
            $trx = TrxRecruitmentTiu5::where(['id_identitas' => $id[0]])->orderBy('id', 'asc')->get();
            $benar = $trx->sum('cek');
            $norma = MtrRecruitmentSoalTiu5Norma::whereRaw('? BETWEEN bawah AND atas', [$benar])->first();
            $sheet->setCellValue('A6', 'Benar');
            $sheet->setCellValue('A7', 'Salah');
            $sheet->setCellValue('A8', 'WS');
            $sheet->setCellValue('A9', 'Kategori');
            $sheet->setCellValue('B6', ': '.$benar);
            $sheet->setCellValue('B7', ': '.(30 - $benar));
            $sheet->setCellValue('B8', ': '.$norma->ws);
            $sheet->setCellValue('B9', ': '.$norma->kategori);
        }
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // $id berisikan array
        // $id[0] = id_identitas
        // $id[1] = jenis
        // $id[2] = urutan
        $id = $this->id;
        $arr = TrxRecruitmentPsychotest::where(['id_identitas'=>$id[0], 'jenis'=>$id[1]])->where('nomor', '>', 0)->orderBy('id', 'asc')->get();
        if(!empty($id[2])){
            $arr = TrxRecruitmentPsychotestLog::where(['urutan'=>$id[2], 'id_identitas'=>$id[0], 'jenis'=>$id[1]])->where('nomor', '>', 0)->orderBy('id', 'asc')->get();
        }
        $data = [[]];
        // tipe 1
        if($id[1] == 1){
            $data1 = [];
            $data2 = [];
            $data3 = [];
            $data4 = [];
            $data5 = [];
            $angka1 = 70;
            $angka2 = 145;
            $angka3 = 220;
            foreach($arr as $_arr){
                $jawaban1 = 'A';
                if($_arr->jawaban1 == 2){
                    $jawaban1 = 'B';
                }
                if($_arr->nomor % 5 == 1){
                    array_push($data1, $_arr->nomor, $jawaban1);
                }elseif($_arr->nomor % 5 == 2){
                    array_push($data2, $_arr->nomor, $jawaban1);
                }elseif($_arr->nomor % 5 == 3){
                    array_push($data3, $_arr->nomor, $jawaban1);
                }elseif($_arr->nomor % 5 == 4){
                    array_push($data4, $_arr->nomor, $jawaban1);
                }elseif($_arr->nomor % 5 == 0){
                    array_push($data5, $_arr->nomor, $jawaban1);
                }
                // 
                if(in_array($_arr->nomor, [$angka1+1, $angka2+1, $angka3+1])){
                    array_push($data, $data1);
                    $data1 = [];
                }
                elseif(in_array($_arr->nomor, [$angka1+2, $angka2+2, $angka3+2])){
                    array_push($data, $data2);
                    $data2 = [];
                }
                elseif(in_array($_arr->nomor, [$angka1+3, $angka2+3, $angka3+3])){
                    array_push($data, $data3);
                    $data3 = [];
                }
                elseif(in_array($_arr->nomor, [$angka1+4, $angka2+4, $angka3+4])){
                    array_push($data, $data4);
                    $data4 = [];
                }
                elseif(in_array($_arr->nomor, [$angka1+5, $angka2+5, $angka3+5])){
                    array_push($data, $data5);
                    array_push($data, [[]]);
                    array_push($data, [[]]);
                    $data5 = [];
                }
            }
        }
        // tipe 2
        elseif($id[1] == 2){
            foreach($arr as $_arr){
                $jawaban1 = 'A';
                if($_arr->jawaban1 == 2){
                    $jawaban1 = 'B';
                }
                $data1 = [];
                array_push($data1, $_arr->nomor, 'a. '.$_arr->soal1, $jawaban1);
                array_push($data, $data1);
                $data1 = [];
                array_push($data1, '', 'b. '.$_arr->soal2, '');
                array_push($data, $data1);
            }
        }
        // // tipe 2 tidak jadi
        // elseif($id[1] == 2){
        //     $data1 = [];
        //     $data2 = [];
        //     $data3 = [];
        //     $data4 = [];
        //     $data5 = [];
        //     $data6 = [];
        //     $data7 = [];
        //     $data8 = [];
        //     $data9 = [];
        //     $data10 = [];
        //     foreach($arr as $_arr){
        //         $jawaban1 = 'a';
        //         if($_arr->jawaban1 == 2){
        //             $jawaban1 = 'b';
        //         }
        //         if($_arr->nomor % 10 == 1){
        //             array_push($data1, $jawaban1, $_arr->nomor);
        //         }
        //         elseif($_arr->nomor % 10 == 2){
        //             array_push($data2, $jawaban1, $_arr->nomor);
        //         }
        //         elseif($_arr->nomor % 10 == 3){
        //             array_push($data3, $jawaban1, $_arr->nomor);
        //         }
        //         elseif($_arr->nomor % 10 == 4){
        //             array_push($data4, $jawaban1, $_arr->nomor);
        //         }
        //         elseif($_arr->nomor % 10 == 5){
        //             array_push($data5, $jawaban1, $_arr->nomor);
        //         }
        //         elseif($_arr->nomor % 10 == 6){
        //             array_push($data6, $jawaban1, $_arr->nomor);
        //         }
        //         elseif($_arr->nomor % 10 == 7){
        //             array_push($data7, $jawaban1, $_arr->nomor);
        //         }
        //         elseif($_arr->nomor % 10 == 8){
        //             array_push($data8, $jawaban1, $_arr->nomor);
        //         }
        //         elseif($_arr->nomor % 10 == 9){
        //             array_push($data9, $jawaban1, $_arr->nomor);
        //         }
        //         elseif($_arr->nomor % 10 == 0){
        //             array_push($data10, $jawaban1, $_arr->nomor);
        //         }
        //     }
        //     array_push($data, array_reverse($data1));
        //     array_push($data, array_reverse($data2));
        //     array_push($data, array_reverse($data3));
        //     array_push($data, array_reverse($data4));
        //     array_push($data, array_reverse($data5));
        //     array_push($data, array_reverse($data6));
        //     array_push($data, array_reverse($data7));
        //     array_push($data, array_reverse($data8));
        //     array_push($data, array_reverse($data9));
        //     array_push($data, array_reverse($data10));
        // }
        // tipe 3
        elseif($id[1] == 3){
            $mulai = 16;
            $data11 = [];
            $data12 = [];
            $data13 = [];
            $data14 = [];
            $data15 = [];
            $data21 = [];
            $data22 = [];
            $data23 = [];
            $data24 = [];
            $data25 = [];
            $data31 = [];
            $data32 = [];
            $data33 = [];
            $data34 = [];
            $data35 = [];
            $data41 = [];
            $data42 = [];
            $data43 = [];
            $data44 = [];
            $data45 = [];
            $data51 = [];
            $data52 = [];
            $data53 = [];
            $data54 = [];
            $data55 = [];
            $data61 = [];
            $data62 = [];
            $data63 = [];
            $data64 = [];
            $data65 = [];
            $data71 = [];
            $data72 = [];
            $data73 = [];
            $data74 = [];
            $data75 = [];
            $data81 = [];
            $data82 = [];
            $data83 = [];
            $data84 = [];
            $data85 = [];
            foreach($arr as $_arr){
                if($_arr->nomor % 8 == 1){
                    $jawaban111 = ['','0'];
                    $jawaban112 = ['','0'];
                    $jawaban113 = ['','0'];
                    $jawaban114 = ['','0'];
                    $jawaban211 = ['','0'];
                    $jawaban212 = ['','0'];
                    $jawaban213 = ['','0'];
                    $jawaban214 = ['','0'];
                    // jawaban1
                    if($_arr->jawaban1 == 1){
                        $jawaban111 = ['X',1];
                    }elseif($_arr->jawaban1 == 2){
                        $jawaban112 = ['X',2];
                    }elseif($_arr->jawaban1 == 3){
                        $jawaban113 = ['X',3];
                    }elseif($_arr->jawaban1 == 4){
                        $jawaban114 = ['X',4];
                    }
                    // jawaban2
                    if($_arr->jawaban2 == 1){
                        $jawaban211 = ['X',1];
                    }elseif($_arr->jawaban2 == 2){
                        $jawaban212 = ['X',2];
                    }elseif($_arr->jawaban2 == 3){
                        $jawaban213 = ['X',3];
                    }elseif($_arr->jawaban2 == 4){
                        $jawaban214 = ['X',4];
                    }
                    array_push($data11, $_arr->nomor, $jawaban111[0], $jawaban111[1], $jawaban211[0], $jawaban211[1], $_arr->soal1, '');
                    array_push($data12, '', $jawaban112[0], $jawaban112[1], $jawaban212[0], $jawaban212[1], $_arr->soal2, '');
                    array_push($data13, '', $jawaban113[0], $jawaban113[1], $jawaban213[0], $jawaban213[1], $_arr->soal3, '');
                    array_push($data14, '', $jawaban114[0], $jawaban114[1], $jawaban214[0], $jawaban214[1], $_arr->soal4, '');
                    array_push($data15, '', 1, $_arr->jawaban1, 1, $_arr->jawaban2, '', '');
                }
                elseif($_arr->nomor % 8 == 2){
                    $jawaban121 = ['','0'];
                    $jawaban122 = ['','0'];
                    $jawaban123 = ['','0'];
                    $jawaban124 = ['','0'];
                    $jawaban221 = ['','0'];
                    $jawaban222 = ['','0'];
                    $jawaban223 = ['','0'];
                    $jawaban224 = ['','0'];
                    // jawaban1
                    if($_arr->jawaban1 == 1){
                        $jawaban121 = ['X',1];
                    }elseif($_arr->jawaban1 == 2){
                        $jawaban122 = ['X',2];
                    }elseif($_arr->jawaban1 == 3){
                        $jawaban123 = ['X',3];
                    }elseif($_arr->jawaban1 == 4){
                        $jawaban124 = ['X',4];
                    }
                    // jawaban2
                    if($_arr->jawaban2 == 1){
                        $jawaban221 = ['X',1];
                    }elseif($_arr->jawaban2 == 2){
                        $jawaban222 = ['X',2];
                    }elseif($_arr->jawaban2 == 3){
                        $jawaban223 = ['X',3];
                    }elseif($_arr->jawaban2 == 4){
                        $jawaban224 = ['X',4];
                    }
                    array_push($data21, $_arr->nomor, $jawaban121[0], $jawaban121[1], $jawaban221[0], $jawaban221[1], $_arr->soal1, '');
                    array_push($data22, '', $jawaban122[0], $jawaban122[1], $jawaban222[0], $jawaban222[1], $_arr->soal2, '');
                    array_push($data23, '', $jawaban123[0], $jawaban123[1], $jawaban223[0], $jawaban223[1], $_arr->soal3, '');
                    array_push($data24, '', $jawaban124[0], $jawaban124[1], $jawaban224[0], $jawaban224[1], $_arr->soal4, '');
                    array_push($data25, '', 1, $_arr->jawaban1, 1, $_arr->jawaban2, '', '');
                }
                elseif($_arr->nomor % 8 == 3){
                    $jawaban131 = ['','0'];
                    $jawaban132 = ['','0'];
                    $jawaban133 = ['','0'];
                    $jawaban134 = ['','0'];
                    $jawaban231 = ['','0'];
                    $jawaban232 = ['','0'];
                    $jawaban233 = ['','0'];
                    $jawaban234 = ['','0'];
                    // jawaban1
                    if($_arr->jawaban1 == 1){
                        $jawaban131 = ['X',1];
                    }elseif($_arr->jawaban1 == 2){
                        $jawaban132 = ['X',2];
                    }elseif($_arr->jawaban1 == 3){
                        $jawaban133 = ['X',3];
                    }elseif($_arr->jawaban1 == 4){
                        $jawaban134 = ['X',4];
                    }
                    // jawaban2
                    if($_arr->jawaban2 == 1){
                        $jawaban231 = ['X',1];
                    }elseif($_arr->jawaban2 == 2){
                        $jawaban232 = ['X',2];
                    }elseif($_arr->jawaban2 == 3){
                        $jawaban233 = ['X',3];
                    }elseif($_arr->jawaban2 == 4){
                        $jawaban234 = ['X',4];
                    }
                    array_push($data31, $_arr->nomor, $jawaban131[0], $jawaban131[1], $jawaban231[0], $jawaban231[1], $_arr->soal1, '');
                    array_push($data32, '', $jawaban132[0], $jawaban132[1], $jawaban232[0], $jawaban232[1], $_arr->soal2, '');
                    array_push($data33, '', $jawaban133[0], $jawaban133[1], $jawaban233[0], $jawaban233[1], $_arr->soal3, '');
                    array_push($data34, '', $jawaban134[0], $jawaban134[1], $jawaban234[0], $jawaban234[1], $_arr->soal4, '');
                    array_push($data35, '', 1, $_arr->jawaban1, 1, $_arr->jawaban2, '', '');
                }
                elseif($_arr->nomor % 8 == 4){
                    $jawaban141 = ['','0'];
                    $jawaban142 = ['','0'];
                    $jawaban143 = ['','0'];
                    $jawaban144 = ['','0'];
                    $jawaban241 = ['','0'];
                    $jawaban242 = ['','0'];
                    $jawaban243 = ['','0'];
                    $jawaban244 = ['','0'];
                    // jawaban1
                    if($_arr->jawaban1 == 1){
                        $jawaban141 = ['X',1];
                    }elseif($_arr->jawaban1 == 2){
                        $jawaban142 = ['X',2];
                    }elseif($_arr->jawaban1 == 3){
                        $jawaban143 = ['X',3];
                    }elseif($_arr->jawaban1 == 4){
                        $jawaban144 = ['X',4];
                    }
                    // jawaban2
                    if($_arr->jawaban2 == 1){
                        $jawaban241 = ['X',1];
                    }elseif($_arr->jawaban2 == 2){
                        $jawaban242 = ['X',2];
                    }elseif($_arr->jawaban2 == 3){
                        $jawaban243 = ['X',3];
                    }elseif($_arr->jawaban2 == 4){
                        $jawaban244 = ['X',4];
                    }
                    array_push($data41, $_arr->nomor, $jawaban141[0], $jawaban141[1], $jawaban241[0], $jawaban241[1], $_arr->soal1, '');
                    array_push($data42, '', $jawaban142[0], $jawaban142[1], $jawaban242[0], $jawaban242[1], $_arr->soal2, '');
                    array_push($data43, '', $jawaban143[0], $jawaban143[1], $jawaban243[0], $jawaban243[1], $_arr->soal3, '');
                    array_push($data44, '', $jawaban144[0], $jawaban144[1], $jawaban244[0], $jawaban244[1], $_arr->soal4, '');
                    array_push($data45, '', 1, $_arr->jawaban1, 1, $_arr->jawaban2, '', '');
                }
                elseif($_arr->nomor % 8 == 5){
                    $jawaban151 = ['','0'];
                    $jawaban152 = ['','0'];
                    $jawaban153 = ['','0'];
                    $jawaban154 = ['','0'];
                    $jawaban251 = ['','0'];
                    $jawaban252 = ['','0'];
                    $jawaban253 = ['','0'];
                    $jawaban254 = ['','0'];
                    // jawaban1
                    if($_arr->jawaban1 == 1){
                        $jawaban151 = ['X',1];
                    }elseif($_arr->jawaban1 == 2){
                        $jawaban152 = ['X',2];
                    }elseif($_arr->jawaban1 == 3){
                        $jawaban153 = ['X',3];
                    }elseif($_arr->jawaban1 == 4){
                        $jawaban154 = ['X',4];
                    }
                    // jawaban2
                    if($_arr->jawaban2 == 1){
                        $jawaban251 = ['X',1];
                    }elseif($_arr->jawaban2 == 2){
                        $jawaban252 = ['X',2];
                    }elseif($_arr->jawaban2 == 3){
                        $jawaban253 = ['X',3];
                    }elseif($_arr->jawaban2 == 4){
                        $jawaban254 = ['X',4];
                    }
                    array_push($data51, $_arr->nomor, $jawaban151[0], $jawaban151[1], $jawaban251[0], $jawaban251[1], $_arr->soal1, '');
                    array_push($data52, '', $jawaban152[0], $jawaban152[1], $jawaban252[0], $jawaban252[1], $_arr->soal2, '');
                    array_push($data53, '', $jawaban153[0], $jawaban153[1], $jawaban253[0], $jawaban253[1], $_arr->soal3, '');
                    array_push($data54, '', $jawaban154[0], $jawaban154[1], $jawaban254[0], $jawaban254[1], $_arr->soal4, '');
                    array_push($data55, '', 1, $_arr->jawaban1, 1, $_arr->jawaban2, '', '');
                }
                elseif($_arr->nomor % 8 == 6){
                    $jawaban161 = ['','0'];
                    $jawaban162 = ['','0'];
                    $jawaban163 = ['','0'];
                    $jawaban164 = ['','0'];
                    $jawaban261 = ['','0'];
                    $jawaban262 = ['','0'];
                    $jawaban263 = ['','0'];
                    $jawaban264 = ['','0'];
                    // jawaban1
                    if($_arr->jawaban1 == 1){
                        $jawaban161 = ['X',1];
                    }elseif($_arr->jawaban1 == 2){
                        $jawaban162 = ['X',2];
                    }elseif($_arr->jawaban1 == 3){
                        $jawaban163 = ['X',3];
                    }elseif($_arr->jawaban1 == 4){
                        $jawaban164 = ['X',4];
                    }
                    // jawaban2
                    if($_arr->jawaban2 == 1){
                        $jawaban261 = ['X',1];
                    }elseif($_arr->jawaban2 == 2){
                        $jawaban262 = ['X',2];
                    }elseif($_arr->jawaban2 == 3){
                        $jawaban263 = ['X',3];
                    }elseif($_arr->jawaban2 == 4){
                        $jawaban264 = ['X',4];
                    }
                    array_push($data61, $_arr->nomor, $jawaban161[0], $jawaban161[1], $jawaban261[0], $jawaban261[1], $_arr->soal1, '');
                    array_push($data62, '', $jawaban162[0], $jawaban162[1], $jawaban262[0], $jawaban262[1], $_arr->soal2, '');
                    array_push($data63, '', $jawaban163[0], $jawaban163[1], $jawaban263[0], $jawaban263[1], $_arr->soal3, '');
                    array_push($data64, '', $jawaban164[0], $jawaban164[1], $jawaban264[0], $jawaban264[1], $_arr->soal4, '');
                    array_push($data65, '', 1, $_arr->jawaban1, 1, $_arr->jawaban2, '', '');
                }
                elseif($_arr->nomor % 8 == 7){
                    $jawaban171 = ['','0'];
                    $jawaban172 = ['','0'];
                    $jawaban173 = ['','0'];
                    $jawaban174 = ['','0'];
                    $jawaban271 = ['','0'];
                    $jawaban272 = ['','0'];
                    $jawaban273 = ['','0'];
                    $jawaban274 = ['','0'];
                    // jawaban1
                    if($_arr->jawaban1 == 1){
                        $jawaban171 = ['X',1];
                    }elseif($_arr->jawaban1 == 2){
                        $jawaban172 = ['X',2];
                    }elseif($_arr->jawaban1 == 3){
                        $jawaban173 = ['X',3];
                    }elseif($_arr->jawaban1 == 4){
                        $jawaban174 = ['X',4];
                    }
                    // jawaban2
                    if($_arr->jawaban2 == 1){
                        $jawaban271 = ['X',1];
                    }elseif($_arr->jawaban2 == 2){
                        $jawaban272 = ['X',2];
                    }elseif($_arr->jawaban2 == 3){
                        $jawaban273 = ['X',3];
                    }elseif($_arr->jawaban2 == 4){
                        $jawaban274 = ['X',4];
                    }
                    array_push($data71, $_arr->nomor, $jawaban171[0], $jawaban171[1], $jawaban271[0], $jawaban271[1], $_arr->soal1, '');
                    array_push($data72, '', $jawaban172[0], $jawaban172[1], $jawaban272[0], $jawaban272[1], $_arr->soal2, '');
                    array_push($data73, '', $jawaban173[0], $jawaban173[1], $jawaban273[0], $jawaban273[1], $_arr->soal3, '');
                    array_push($data74, '', $jawaban174[0], $jawaban174[1], $jawaban274[0], $jawaban274[1], $_arr->soal4, '');
                    array_push($data75, '', 1, $_arr->jawaban1, 1, $_arr->jawaban2, '', '');
                }
                elseif($_arr->nomor % 8 == 0){
                    $jawaban181 = ['','0'];
                    $jawaban182 = ['','0'];
                    $jawaban183 = ['','0'];
                    $jawaban184 = ['','0'];
                    $jawaban281 = ['','0'];
                    $jawaban282 = ['','0'];
                    $jawaban283 = ['','0'];
                    $jawaban284 = ['','0'];
                    // jawaban1
                    if($_arr->jawaban1 == 1){
                        $jawaban181 = ['X',1];
                    }elseif($_arr->jawaban1 == 2){
                        $jawaban182 = ['X',2];
                    }elseif($_arr->jawaban1 == 3){
                        $jawaban183 = ['X',3];
                    }elseif($_arr->jawaban1 == 4){
                        $jawaban184 = ['X',4];
                    }
                    // jawaban2
                    if($_arr->jawaban2 == 1){
                        $jawaban281 = ['X',1];
                    }elseif($_arr->jawaban2 == 2){
                        $jawaban282 = ['X',2];
                    }elseif($_arr->jawaban2 == 3){
                        $jawaban283 = ['X',3];
                    }elseif($_arr->jawaban2 == 4){
                        $jawaban284 = ['X',4];
                    }
                    array_push($data81, $_arr->nomor, $jawaban181[0], $jawaban181[1], $jawaban281[0], $jawaban281[1], $_arr->soal1, '');
                    array_push($data82, '', $jawaban182[0], $jawaban182[1], $jawaban282[0], $jawaban282[1], $_arr->soal2, '');
                    array_push($data83, '', $jawaban183[0], $jawaban183[1], $jawaban283[0], $jawaban283[1], $_arr->soal3, '');
                    array_push($data84, '', $jawaban184[0], $jawaban184[1], $jawaban284[0], $jawaban284[1], $_arr->soal4, '');
                    array_push($data85, '', 1, $_arr->jawaban1, 1, $_arr->jawaban2, '', '');
                }
                // 
                if($_arr->nomor == $mulai+1){
                    array_push($data, $data11);
                    array_push($data, $data12);
                    array_push($data, $data13);
                    array_push($data, $data14);
                    array_push($data, $data15);
                    array_push($data, $this->headings());
                }
                elseif($_arr->nomor == $mulai+2){
                    array_push($data, $data21);
                    array_push($data, $data22);
                    array_push($data, $data23);
                    array_push($data, $data24);
                    array_push($data, $data25);
                    array_push($data, $this->headings());
                }
                elseif($_arr->nomor == $mulai+3){
                    array_push($data, $data31);
                    array_push($data, $data32);
                    array_push($data, $data33);
                    array_push($data, $data34);
                    array_push($data, $data35);
                    array_push($data, $this->headings());
                }
                elseif($_arr->nomor == $mulai+4){
                    array_push($data, $data41);
                    array_push($data, $data42);
                    array_push($data, $data43);
                    array_push($data, $data44);
                    array_push($data, $data45);
                    array_push($data, $this->headings());
                }
                elseif($_arr->nomor == $mulai+5){
                    array_push($data, $data51);
                    array_push($data, $data52);
                    array_push($data, $data53);
                    array_push($data, $data54);
                    array_push($data, $data55);
                    array_push($data, $this->headings());
                }
                elseif($_arr->nomor == $mulai+6){
                    array_push($data, $data61);
                    array_push($data, $data62);
                    array_push($data, $data63);
                    array_push($data, $data64);
                    array_push($data, $data65);
                    array_push($data, $this->headings());
                }
                elseif($_arr->nomor == $mulai+7){
                    array_push($data, $data71);
                    array_push($data, $data72);
                    array_push($data, $data73);
                    array_push($data, $data74);
                    array_push($data, $data75);
                    array_push($data, $this->headings());
                }
                elseif($_arr->nomor == $mulai+8){
                    array_push($data, $data81);
                    array_push($data, $data82);
                    array_push($data, $data83);
                    array_push($data, $data84);
                    array_push($data, $data85);
                }
            }
        }
        // tipe 6
        elseif($id[1] == 6){
            $data1 = [];
            array_push($data1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            array_push($data, $data1);
            $arr = TrxRecruitmentTiu5::where(['id_identitas'=>$id[0], 'jenis'=>$id[1]])->where('nomor', '>', 0)->orderBy('id', 'asc')->get();
            foreach ($arr as $index => $_arr) {
                $hasil = 'Salah';
                if($_arr->cek == 1){
                    $hasil = 'Benar';
                }
                $data1 = [];
                array_push($data1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', $_arr->nomor, $_arr->jawaban, $_arr->kunci, $hasil);
                array_push($data, $data1);
                for ($x = 0; $x < 5; $x++) {
                    $data1 = [];
                    array_push($data1, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
                    array_push($data, $data1);
                }
                // array_push($data1, $_arr->soal, $_arr->jawaban, $_arr->kunci, $_arr->cek);
            }
        }
        return new Collection($data);
    }

    public function drawings()
    {
        $id = $this->id;
        $drawings = [];
        // tipe 6
        if($id[1] == 6){
            $startRow = 12;
            $arr = TrxRecruitmentTiu5::where(['id_identitas'=>$id[0], 'jenis'=>$id[1]])->where('nomor', '>', 0)->orderBy('id', 'asc')->get();
            foreach ($arr as $index => $_arr) {
                // {{ asset('assets/images/matta/tiu5/soal'.$soal->nomor.'.png') }}
                $path = public_path('assets/images/matta/tiu5/' . $_arr->soal);
                if (file_exists($path)) {
                    $drawing = new Drawing();
                    // $drawing->setName('Gambar Soal');
                    // $drawing->setDescription('Soal Gambar');
                    $drawing->setPath($path);
                    $drawing->setHeight(100);
                    $drawing->setCoordinates('A' . ($startRow + ($index * 6)));
                    $drawings[] = $drawing;
                } else {
                    // \Log::warning("Gambar tidak ditemukan: $path");
                }
            }
        }
        return $drawings;
    }

    public function headings(): array
    {
        $id = $this->id;
        // tipe 1
        if($id[1] == 1){
            return ['No','#','No','#','No','#','No','#','No','#','No','#','No','#','No','#','No','#','No','#','No','#','No','#','No','#','No','#','No','#',];
        }
        // tipe 2
        elseif($id[1] == 2){
            return ['No','Soal','#',];
        }
        // // tipe 2 tidak jadi
        // elseif($id[1] == 2){
        //     return ['No','#','No','#','No','#','No','#','No','#','No','#','No','#','No','#','No','#',];
        // }
        // tipe 3
        elseif($id[1] == 3){
            return ['No','P', '','K', '','Gambaran Diri', '','No','P', '','K', '','Gambaran Diri', '','No','P', '','K', '','Gambaran Diri',];
        }
        // tipe 6
        elseif($id[1] == 6){
            return ['Soal', '', '', '', '', '', '', '', '', '', '', '', '', '', 'No','Jawaban','Kunci','Hasil',];
        }
    }

    public function title(): string
    {
        return 'Answer';
    }
}
