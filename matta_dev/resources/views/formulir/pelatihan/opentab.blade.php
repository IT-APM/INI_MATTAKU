<!DOCTYPE html>
<html>
<head>
    <title>Open Tab</title>
    <script>
        window.onload = function() {
            // Buka tab baru
            window.open("{!! $downloadUrl !!}", "_blank");

            // Redirect kembali ke halaman lain jika perlu
            window.location.href = "/";
        };
    </script>
</head>
<body>
    <p>Membuka link download di tab baru...</p>
</body>
</html>
