@extends('layouts.simple.master')
@section('title', 'Biaya Perjalanan Dinas')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
    <h3>Pembatalan 
        @if ($trxMaster->luar_dalam == 'ca_dalam')
            Cash Advance Perjalanan Dinas Dalam Negeri
        @elseif ($trxMaster->luar_dalam == 'ca_luar')
            Cash Advance Perjalanan Dinas Luar Negeri
        @elseif ($trxMaster->luar_dalam == 'reimburse')
            Reimbursement Perjalanan Dinas Dalam Negeri
        @elseif ($trxMaster->luar_dalam == 'reimburse_luar')
            Reimbursement Perjalanan Dinas Luar Negeri
        @endif
    </h3>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!--  -->
            <div class="col-sm-12 col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" enctype="multipart/form-data" action="{{ $linkAction }}" target="_self"
                            class="needs-validation" novalidate=""
                            onsubmit="return confirm('Are you sure want to submit this data?');">
                            @method('PATCH')
                            @csrf
                            <input type="hidden" id="nik" name="nik" value="{{ $nik }}">
                            <h5>Catatan</h5>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <textarea class="form-control" id="catatan" name="catatan" rows="4"></textarea>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    <button type="submit" name="status_id" class="btn btn-danger"
                                        value="{{ $approve }}">Submit</button>
                                    <a class="btn btn-warning" href="{{ route('bpd.index') }}">Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body typography text-center">
                        <h6>Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan review ini.</h6>
                        <a href="https://alita.id/"><img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}"
                                height="40" alt="Alita" /></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
