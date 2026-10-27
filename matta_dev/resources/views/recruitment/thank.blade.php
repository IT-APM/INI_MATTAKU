<!DOCTYPE html>
<html lang="en">

<head>
    <title>Application Form</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<body>
    <section class="p-2 p-md-2 p-xl-2">
        <div class="container">
            <div class="card border-light-subtle shadow-sm">
                <div class="row g-0 text-end">
                    <div class="col-12 col-md-12"><img class="img-fluid rounded-start w-194 h-73 object-fit-cover"
                            loading="lazy" src="{{ asset('assets/images/matta/alita.png') }}" alt="Alita"></div>
                </div>
                <hr class="mt-2 mb-2" />
                <div class="row g-0">
                    <div class="col-12 col-md-8 p-2">
                        {!! $msg !!}
                    </div>
                    <div class="col-12 col-md-4 p-2">
                        <img class="img-fluid rounded-start w-100 h-100 object-fit-cover" loading="lazy"
                            src="{{ asset('assets/recruitment/thank.jpg') }}" alt="Application Form">
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
</script>
</html>
