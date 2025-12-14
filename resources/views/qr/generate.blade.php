@extends('auth.layouts.master')

@section('title', 'QR Visitors')

@section('content')
<?php
$kodeValid = isset($_GET['kode']) && $_GET['kode'] !== ""
    ? $_GET['kode']
    : "TIDAKVALID";

$isValid = $kodeValid !== "TIDAKVALID";
?>

    <script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
    <style>
        body {
            background: #f2f4f7;
            font-family: "Segoe UI", sans-serif;
        }

        .qr-card {
            max-width: 400px;
            margin: auto;
            margin-top: 40px;
            background: #fff;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.12);
        }

        #qr-container {
            display: none;
        }

        #qr {
            width: 100%;
        }

        .qr-code-box {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .code-text {
            font-size: 1.2rem;
            font-weight: 600;
            margin-top: 15px;
        }
    </style>

<div class="container">
    <div class="qr-card text-center">
        <!-- Tampilan Tidak Valid -->
        <div id="invalid-container" class="text-center my-4" style="display:none;">
            <div style="font-size:64px;">❌</div>
            <h5 class="mt-3 text-danger fw-bold">QR Code Tidak Valid</h5>
            <p class="text-muted mb-0">
                Silakan minta undangan yang benar<br>
                atau hubungi panitia acara
            </p>
        </div>

        <!--<h4 class="mb-3">QR Code</h4>-->

        <!-- Loading (1.5 detik) -->
        <div id="loading" class="text-center my-4">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"></div>
            <p class="mt-2 text-muted">Mempersiapkan kode...</p>
        </div>

        <!-- QR tampil setelah loading -->
        <div id="qr-container">
            <div class="qr-code-box">
                <canvas id="qr"></canvas>
            </div>

            <!--<div class="code-text text-primary text-break"><?= $kodeValid ?></div>-->
            <p class="text-muted mt-2 mb-0">Scan QR ini untuk validasi</p>
        </div>

    </div>
</div>

<script>
var kode = "<?= $kodeValid ?>";
var isValid = <?= $isValid ? 'true' : 'false' ?>;

setTimeout(() => {

    // Hilangkan loading
    document.getElementById("loading").style.display = "none";

    if (!isValid) {
        // Tampilkan error
        document.getElementById("invalid-container").style.display = "block";
        return;
    }

    // Tampilkan QR
    document.getElementById("qr-container").style.display = "block";

    new QRious({
        element: document.getElementById('qr'),
        size: 300,
        value: kode
    });

}, 700);
</script>

@endsection
