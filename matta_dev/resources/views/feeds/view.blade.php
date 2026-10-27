@extends('layouts.simple.master')
@section('title', 'Feeds')

@section('css')
@endsection

@section('style')
@endsection

@section('breadcrumb-title')
<h3>Feeds</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Feeds</li>
<li class="breadcrumb-item active">Feeds</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="knowledgebase-bg"><img class="bg-img-cover bg-center"
                    src="{{ asset('assets/images/knowledgebase/bg_1.jpg') }}" alt="looginpage"></div>
            <!-- <div class="knowledgebase-search">
                <div>
                    <h3>How Can I help you?</h3>
                    <form class="form-inline" action="#" method="get">
                        <div class="form-group w-100"><i data-feather="search"></i>
                            <input class="form-control-plaintext w-100" type="text" placeholder="Type question here"
                                title="">
                        </div>
                    </form>
                </div>
            </div> -->
        </div>
        <div class="header-faq">
            <h5 class="mb-0">Feeds Aktif</h5>
        </div>
        @foreach ($feed as $data)
        @if($data->is_active == 1)
        <div class="col-xl-4 col-sm-6">
            <div class="card bg-primary">
                <div class="card-body"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal"
                        data-bs-target="#exampleModalLong{{ $data->id }}"><i class="m-r-30"
                            data-feather="file-text"></i></button>
                    <div class="media faq-widgets">
                        <div class="media-body">
                            <h5>{{ $data->title }}</h5>
                        </div>
                    </div>
                    {{ $data->created_at }}
                </div>
            </div>
        </div>
        <!-- start Scrolling long content-->
        <div class="modal fade" id="exampleModalLong{{ $data->id }}" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ $data->title }}</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">{!! $data->content !!}</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- end Scrolling long content-->
        @endif
        @endforeach
        <div class="col-lg-12">
            <div class="header-faq">
                <h5 class="mb-0">Feeds Non Aktif</h5>
            </div>
            <div class="row">
                @foreach ($feed as $data)
                @if($data->is_active == 0)
                <div class="col-xl-4 col-md-6">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="modal"
                                        data-bs-target="#exampleModalLong{{ $data->id }}"><i class="m-r-30"
                                            data-feather="file-text"></i></button>
                                    <div class="media">
                                        <div class="media-body">
                                            <h6 class="f-w-600">{{ $data->title }}</h6>
                                        </div>
                                    </div>
                                    {{ $data->created_at }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- start Scrolling long content-->
                <div class="modal fade" id="exampleModalLong{{ $data->id }}" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">{{ $data->title }}</h5>
                                <button class="btn-close" type="button" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">{!! $data->content !!}</div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end Scrolling long content-->
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

@endsection