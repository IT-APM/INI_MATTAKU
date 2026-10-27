<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\MtrUtility;
use App\Models\MtrReminderSp;

class UtilitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::allows('hc_nkp')){
            return redirect()->route('error-403');
        }
        $utilitas = MtrUtility::get();
        $reminder = MtrReminderSp::get();
        // return response()->json( [ 'utilitas' => $utilitas ], 200);
        return view('master.utilitas.upsert', compact('utilitas', 'reminder'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all, $id);
        $utilitas = MtrUtility::find(1);
        $utilitas->informasi = $request->_1;
        $utilitas->update();
        // id=11 sama dengan id=1
        $utilitas = MtrUtility::find(11);
        $utilitas->informasi = $request->_1;
        $utilitas->update();
        // 
        $utilitas = MtrUtility::find(5);
        $utilitas->informasi = $request->_5;
        $utilitas->update();
        $utilitas = MtrUtility::find(6);
        $utilitas->informasi = $request->_6;
        $utilitas->update();
        $utilitas = MtrUtility::find(7);
        $utilitas->informasi = $request->_7;
        $utilitas->update();
        $utilitas = MtrUtility::find(12);
        $utilitas->informasi = $request->_12;
        $utilitas->update();
        $utilitas = MtrUtility::find(13);
        $utilitas->informasi = $request->_13;
        $utilitas->update();
        $utilitas = MtrUtility::find(14);
        $utilitas->informasi = $request->_14;
        $utilitas->update();
        $utilitas = MtrUtility::find(15);
        $utilitas->informasi = $request->_15;
        $utilitas->update();
        $utilitas = MtrUtility::find(16);
        $utilitas->informasi = $request->_16;
        $utilitas->update();
        $utilitas = MtrUtility::find(17);
        $utilitas->informasi = $request->_17;
        $utilitas->update();
        $utilitas = MtrUtility::find(18);
        $utilitas->informasi = $request->_18;
        $utilitas->update();
        $utilitas = MtrUtility::find(19);
        $utilitas->informasi = $request->_19;
        $utilitas->update();
        $utilitas = MtrUtility::find(20);
        $utilitas->informasi = $request->_20;
        $utilitas->update();
        $utilitas = MtrUtility::find(21);
        $utilitas->informasi = $request->_21;
        $utilitas->update();
        $utilitas = MtrUtility::find(22);
        $utilitas->informasi = $request->_22;
        $utilitas->update();
        $utilitas = MtrUtility::find(23);
        $utilitas->informasi = $request->_23;
        $utilitas->update();
        $utilitas = MtrUtility::find(24);
        $utilitas->informasi = $request->_24;
        $utilitas->update();
        $utilitas = MtrUtility::find(25);
        $utilitas->informasi = $request->_25;
        $utilitas->update();
        $utilitas = MtrUtility::find(26);
        $utilitas->informasi = $request->_26;
        $utilitas->update();
        $utilitas = MtrUtility::find(27);
        $utilitas->informasi = $request->_27;
        $utilitas->update();
        $utilitas = MtrUtility::find(28);
        $utilitas->informasi = $request->_28;
        $utilitas->update();
        $utilitas = MtrUtility::find(29);
        $utilitas->informasi = $request->_29;
        $utilitas->update();
        $utilitas = MtrUtility::find(31);
        $utilitas->informasi = $request->_31;
        $utilitas->update();
        $utilitas = MtrUtility::find(33);
        $utilitas->informasi = $request->_33;
        $utilitas->update();
        $utilitas = MtrUtility::find(37);
        $utilitas->informasi = $request->_37;
        $utilitas->update();
        $utilitas = MtrUtility::find(38);
        $utilitas->informasi = $request->_38;
        $utilitas->update();
        $utilitas = MtrUtility::find(39);
        $utilitas->informasi = $request->_39;
        $utilitas->update();
        $utilitas = MtrUtility::find(40);
        $utilitas->informasi = $request->_40;
        $utilitas->update();
        $utilitas = MtrUtility::find(41);
        $utilitas->informasi = $request->_41;
        $utilitas->update();
        $utilitas = MtrUtility::find(42);
        $utilitas->informasi = $request->_42;
        $utilitas->update();
        $utilitas = MtrUtility::find(43);
        $utilitas->informasi = $request->_43;
        $utilitas->update();
        $utilitas = MtrUtility::find(44);
        $utilitas->informasi = $request->_44;
        $utilitas->update();
        $utilitas = MtrUtility::find(46);
        $utilitas->informasi = $request->_46;
        $utilitas->update();
        $utilitas = MtrUtility::find(47);
        $utilitas->informasi = $request->_47;
        $utilitas->update();
        $utilitas = MtrUtility::find(53);
        $utilitas->informasi = $request->_53;
        $utilitas->update();
        return redirect('utilitas')->with('msg', 'Data is saved successfully.');
    }
    
    public function update_sp(Request $request, $id)
    {
        // dd($request->all(), $id);
        $reminder = MtrReminderSp::find(1);
        $reminder->isi = $request->sp_1;
        $reminder->update();
        $reminder = MtrReminderSp::find(2);
        $reminder->isi = $request->sp_2;
        $reminder->update();
        $reminder = MtrReminderSp::find(3);
        $reminder->isi = $request->sp_3;
        $reminder->update();
        return redirect('utilitas')->with('msg', 'Data is saved successfully.');
    }
    
    public function update_payroll(Request $request, $id)
    {
        // dd($request->all(), $id);
        $utilitas = MtrUtility::find(32);
        $utilitas->informasi = $request->_32;
        $utilitas->update();
        $utilitas = MtrUtility::find(36);
        $utilitas->informasi = $request->_36;
        $utilitas->update();
        $utilitas = MtrUtility::find(45);
        $utilitas->informasi = $request->_45;
        $utilitas->update();
        return redirect('utilitas')->with('msg', 'Data is saved successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
