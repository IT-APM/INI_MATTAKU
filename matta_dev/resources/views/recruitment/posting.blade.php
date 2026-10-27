<!DOCTYPE html>
<html>

<head>
    <title>@if(!empty($_trx)){{ $trx->_pekerjaan->pekerjaan }}@else Job Vacancy @endif</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/vendors/bootstrap.css')}}">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
</head>
<style>
    /* body {
        background-image: url('{{ asset('assets/recruitment/background_page-0001.jpg') }}');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        background-size: 1020px;
    } */
</style>

<body>
    @if(!empty($_trx))
    <div class="container" id="content">
        <div class="row">
            <div class="col-6">
                <img class="img-fluid" style="height: 90px" loading="lazy" src="{{ asset('assets/recruitment/header_left.png') }}" alt="image">
            </div>
            <div class="col-6 text-end">
                <img class="img-fluid" style="height: 90px" loading="lazy" src="{{ asset('assets/recruitment/header_right.png') }}" alt="image">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p>&nbsp;</p>
                {!! $_trx->job_description !!}
                <div class="text-center">
                    <h5>Scan here for apply</h5>
                    {{ QrCode::size(100)->generate(route('recruitment.apply', [Crypt::encrypt(array($_trx->id, 'apply'))])) }}
                    {{-- {!! QrCode::size(150)->style('dot')
                    ->eye('circle')
                    ->color(255, 116, 0)
                    ->margin(1)
                    ->generate(route('recruitment.apply', [Crypt::encrypt(array($_trx->id_kebutuhan, 'apply'))])) !!} --}}
                </div>
            </div>
            <div class="col-12 text-center mt-2" style="font-size:20px">
                <a href="https://alita.id/" target="_blank">www.alita.id</a>
            </div>
        </div>
    </div>
    <div class="row mt-2 mb-2">
        <div class="col-12 text-center">
            <button class="btn btn-primary" id="downloadButton">Download as PDF</button>
        </div>
    </div>
    @endif
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script>
        document.getElementById('downloadButton').addEventListener('click', function() {
            const element = document.getElementById('content');
            // Convert HTML to PDF and trigger download
            html2pdf(element, {
                filename: 'Job Vacancy_{{ Carbon\Carbon::now()->timestamp }}.pdf',
                margin: 2,
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
            });
        });
    </script>
</body>

</html>
