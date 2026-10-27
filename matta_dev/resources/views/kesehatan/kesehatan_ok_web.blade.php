@extends('layouts.simple.master')
@section('title', 'Absensi Kesehatan')

@section('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/sweetalert2.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/select2.css')}}">
@endsection

@section('style')
<style type="text/css">
    .content {
        width: 100%;
        max-width: 600px;
    }

    .header {
        padding: 40px 30px 20px 30px;
    }

    .innerpadding {
        padding: 30px 30px 30px 30px;
    }

    .footer {
        background-color: #FF9033;
        padding: 20px 30px 15px 30px;
    }

    .footercopy {
        font-family: sans-serif;
        font-size: 14px;
        color: #ffffff;
    }

    .footercopy a {
        color: #ffffff;
        text-decoration: underline;
    }
    </style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
    <table width="100%" bgcolor="#f6f8f1" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td bgcolor="#c7d8a7" class="header">
                            <table width="70" align="left" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td height="70" style="padding: 0 20px 20px 0;">
                                        <img class="fix" src="{{ asset('assets/images/matta/icon.gif') }}" width="70" height="70" border="0" alt="" />
                                    </td>
                                </tr>
                            </table>
                            <table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%; max-width: 425px;">
                                <tr>
                                    <td height="70">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td class="h2" style="padding: 5px 0 0 0;">
                                                    Absensi Kesehatan
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="innerpadding borderbottom">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td class="h2">
                                        Semangat Pagi,
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bodycopy">
                                        Terima kasih untuk partisipasi dan kerjasamanya untuk melakukan pengisian Absensi Kesehatan ini.</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="footer" bgcolor="#FF9033">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center" class="footercopy">
                                        &reg; PT Alita Praya Mitra - 2021
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding: 20px 0 0 0;">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="150" style="text-align: center; padding: 0 10px 0 10px;">
                                                    <a href="https://alita.id/">
                                                        <img src="{{ asset('assets/images/matta/Alita-New-Log.png') }}" width="150" height="46" alt="Alita" border="0" />
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('assets/js/form-validation-custom.js') }}"></script>
<script src="{{asset('assets/js/sweet-alert/sweetalert.min.js')}}"></script>
<script src="{{asset('assets/js/sweet-alert/app.js')}}"></script>
<script src="{{asset('assets/js/select2/select2.full.min.js')}}"></script>
<script src="{{asset('assets/js/select2/select2-custom.js')}}"></script>
<!--  -->
<!-- source: https://blog.quickadminpanel.com/master-detail-form-in-laravel-jquery-create-order-with-products/ -->
<script type="text/javascript">
// 
</script>
@endsection