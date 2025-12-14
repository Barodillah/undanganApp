@extends('auth.layouts.master')

@section('title', 'Check In Visitor')

@section('content')
<div class="content-wrapper">
    <div class="container py-4">
        <h3 class="text-center mb-3">
            <i class="fas fa-qrcode"></i> Scan QR Code
        </h3>

        <div id="reader" class="mx-auto" style="max-width:500px;"></div>
        <div id="status" class="mt-3 text-center"></div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode"></script>

<script>
let scanning = true;
let html5QrCode;

function startScanner() {
    if (!html5QrCode) html5QrCode = new Html5Qrcode("reader");

    const config = {
        fps: 10,
        qrbox: { width: 300, height: 300 },
        aspectRatio: 1.0,
        experimentalFeatures: { useBarCodeDetectorIfSupported: true }
    };

    html5QrCode.start(
        { facingMode: "environment" },
        config,
        decodedText => {
            if (!scanning) return;
            scanning = false;

            document.getElementById("status").innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-camera"></i> Membaca kode...
                </div>
                <div class="d-flex justify-content-center my-2">
                    <div class="spinner-border text-primary"></div>
                </div>
            `;

            html5QrCode.stop().then(() => {
                setTimeout(() => {

                    const urlParams = new URLSearchParams(window.location.search);
                    const acara = urlParams.get('acara') ?? '';

                    fetch(`{{ route('checkin.verify') }}?code=${encodeURIComponent(decodedText)}&acara=${encodeURIComponent(acara)}`)
                        .then(res => res.json())
                        .then(res => renderResult(res));

                }, 1500);
            });
        }
    );
}

function renderResult(res) {
    const statusEl = document.getElementById("status");

    // =============================
    // SUDAH HADIR
    // =============================
    if (res.message === 'UNDANGAN SUDAH HADIR') {

        let hadirDate = res.hadir_jam ? new Date(res.hadir_jam) : null;
        if (hadirDate) hadirDate = new Date(hadirDate.getTime());

        const formatted = hadirDate
            ? hadirDate.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            })
            : '-';

        statusEl.innerHTML = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-circle"></i> ${res.message}
            </div>

            <div class="border rounded p-3 bg-white text-center">
                <b>Acara</b><br>${res.nama_acara ?? '-'}<br><br>
                <b>Nama</b><br>${res.nama_visitor ?? '-'}<br><br>
                <b>Alamat</b><br>${res.alamat ?? '-'}<br><br>
                <b>Sudah hadir</b><br>${formatted}
            </div>

            <button class="btn btn-primary mt-4" onclick="resetScanner()">
                <i class="fas fa-rotate-right"></i> Scan Ulang
            </button>
        `;
        return;
    }

    // =============================
    // VALID BARU
    // =============================
    if (res.valid) {
        statusEl.innerHTML = `
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> ${res.message}
            </div>

            <div class="border rounded p-3 bg-white text-center">
                <b>Acara</b><br>${res.nama_acara ?? '-'}<br><br>
                <b>Nama</b><br>${res.nama_visitor ?? '-'}<br><br>
                <b>Alamat</b><br>${res.alamat ?? '-'}
            </div>

            <button class="btn btn-primary mt-4" onclick="resetScanner()">
                <i class="fas fa-rotate-right"></i> Scan Ulang
            </button>
        `;
        return;
    }

    // =============================
    // TIDAK VALID → AUTO RESET
    // =============================
    let countdown = 5;

    statusEl.innerHTML = `
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            ${res.message} <br>
            Scan ulang dalam <b><span id="timer">${countdown}</span></b> detik
        </div>

        <button class="btn btn-primary mt-3" onclick="resetScanner()">
            <i class="fas fa-rotate-right"></i> Scan Ulang
        </button>
    `;

    const interval = setInterval(() => {
        countdown--;
        document.getElementById("timer").innerText = countdown;

        if (countdown <= 0) {
            clearInterval(interval);
            resetScanner();
        }
    }, 1000);
}

function resetScanner() {
    scanning = true;
    document.getElementById("status").innerHTML = `
        <div class="alert alert-info">
            <i class="fas fa-camera"></i> Mengaktifkan kamera...
        </div>
    `;
    startScanner();
}

startScanner();
</script>
@endsection
