@extends('admin.layouts.master')

@section('title', 'Visitor')

@section('content')
<style>
.custom-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.6);
    z-index: 9999;
}

.custom-modal-content {
    background: #fff;
    width: 360px;
    margin: 8% auto;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}

.close-modal {
    float: right;
    font-size: 22px;
    cursor: pointer;
}

.cursor-pointer {
    cursor: pointer;
}
</style>

<div class="content-wrapper">
  <div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
      <div class="card">
        <div class="card-body">

          <div class="d-flex justify-content-between mb-3">
            <h4 class="card-title">
            @isset($event)
                Data Visitor – {{ $event->title }}
            @else
                Data Visitor
            @endisset
            </h4>

            @if(auth()->user()->role_id != 2)
            <a href="{{ 
                isset($event)
                ? route('visitors.create', ['event_id' => $event->id])
                : route('visitors.create')
            }}" class="btn btn-primary btn-sm">
            <i class="fas fa-user-plus mr-2"></i>Tambah Visitor
            </a>
            @endif
          </div>

          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif

          <div class="table-responsive">
              <table id="visitorsTable" class="table table-striped dt-responsive nowrap" style="width:100%">
                  <thead>
                      <tr>
                          <th>#</th>
                          @if(!isset($event))
                          <th>Acara</th>
                          @endif
                          <th>Nama</th>
                          <th>Alamat</th>
                          <th>Kode</th>
                          <th>Status</th>
                          @if(auth()->user()->role_id != 2)
                          <th width="150">Aksi</th>
                          @endif
                      </tr>
                  </thead>
                  <tbody>
                      @forelse($visitors as $visitor)
                      @php
                          $kode = strtoupper(preg_replace('/[^A-Za-z]/','',substr($visitor->name,0,3)))
                                  . '-' . str_pad($visitor->event_id,3,'0',STR_PAD_LEFT)
                                  . '-' . str_pad($visitor->id,3,'0',STR_PAD_LEFT);
                      @endphp
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          @if(!isset($event))
                          <td>
                              <span class="badge badge-info">
                                  {{ $visitor->event->title ?? '-' }}
                              </span>
                          </td>
                          @endif
                          <td>{{ $visitor->name }}</td>
                          <td>{{ \Illuminate\Support\Str::limit($visitor->address ?? '-', 10, '...') }}</td>
                          <td>
                              <span class="badge bg-dark text-white cursor-pointer"
                                    onclick="openBarcodeModal('{{ $kode }}', '{{ $visitor->name }}', '{{ $visitor->event->title ?? '-' }}')">
                                  {{ $kode }}
                              </span>
                          </td>
                          <td>
                              <label class="badge badge-{{ 
                                  $visitor->attendance_status == 'hadir' ? 'success' :
                                  ($visitor->attendance_status == 'tidak_hadir' ? 'danger' : 'secondary')
                              }}">
                                  {{ ucfirst(str_replace('_',' ', $visitor->attendance_status)) }}
                              </label>
                          </td>
                          @if(auth()->user()->role_id != 2)
                          <td>
                              <a href="{{ route('visitors.edit', $visitor->id) }}"
                                class="text-warning" title="Edit">
                                  <i class="fas fa-edit"></i>
                              </a>
                              <form action="{{ route('visitors.destroy', $visitor->id) }}"
                                    method="POST" class="d-inline delete-visitor-form">
                                  @csrf
                                  @method('DELETE')
                                  <a type="button" class="text-danger btn-delete-visitor" title="Hapus">
                                      <i class="fas fa-trash-alt"></i>
                                  </a>
                              </form>
                          </td>
                          @endif
                      </tr>
                      @empty
                      <tr>
                          <td colspan="7" class="text-center">Belum ada data visitor</td>
                      </tr>
                      @endforelse
                  </tbody>
              </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<div id="barcodeModal" class="custom-modal">
    <div class="custom-modal-content">

        <span class="close-modal" onclick="closeBarcodeModal()">&times;</span>

        <h5 id="modalNama"></h5>
        <p id="modalAcara" class="text-muted"></p>

        <!-- Loading -->
        <div id="loading">Generating QR...</div>

        <!-- QR -->
        <div id="qr-container" style="display:none">
            <canvas id="qr"></canvas>
        </div>

        <button class="btn btn-dark btn-sm mt-3" onclick="downloadQR()">
            Download QR
        </button>
        <a class="btn btn-info btn-sm mt-3" id="copy-link">
            <i class="fas fa-copy"></i>
        </a>
        <a class="btn btn-info btn-sm mt-3" href="/qr?kode=" target="_blank" id="open-link">
            <i class="fas fa-external-link-alt"></i>
        </a>
    </div>
</div>
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    $('#visitorsTable').DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ entri",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            },
            zeroRecords: "Data tidak ditemukan"
        },
        order: [[0, 'asc']], // default sort by first column
        columnDefs: [
            { orderable: false, targets: -1 }, // kolom aksi tidak bisa sort
            { searchable: false, targets: -1 } // kolom aksi tidak bisa search
        ]
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/js-sha256@0.9.0/src/sha256.min.js"></script>
<script>
let qrInstance;
let currentHash = '';

function openBarcodeModal(kode, nama, acara) {

    document.getElementById('modalNama').innerText = nama;
    document.getElementById('modalAcara').innerText = acara;

    document.getElementById('barcodeModal').style.display = 'block';

    document.getElementById('loading').style.display = 'block';
    document.getElementById('qr-container').style.display = 'none';

    // HASH kode (SHA256)
    currentHash = sha256(kode);
    linkName = nama + '-' + acara;
    document.getElementById('open-link').href = `/qr?kode=${currentHash}`;
    // Delay supaya loading terlihat
    setTimeout(() => {

        document.getElementById('loading').style.display = 'none';
        document.getElementById('qr-container').style.display = 'block';

        qrInstance = new QRious({
            element: document.getElementById('qr'),
            size: 300,
            value: currentHash
        });

    }, 700);
}

function closeBarcodeModal() {
    document.getElementById('barcodeModal').style.display = 'none';
}

function downloadQR() {
    const canvas = document.getElementById('qr');
    const link = document.createElement('a');
    
    link.download = linkName + '.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}
</script>
<script>
document.getElementById('copy-link').addEventListener('click', function () {

    if (!currentHash) {
        alert('QR belum dibuat');
        return;
    }

    // URL tujuan
    const qrUrl = `${window.location.origin}/qr?kode=${currentHash}`;

    // Copy ke clipboard
    navigator.clipboard.writeText(qrUrl).then(() => {

        // Feedback visual
        const btn = this;
        const originalHTML = btn.innerHTML;

        btn.innerHTML = '<i class="fas fa-check"></i>';
        btn.classList.remove('btn-info');
        btn.classList.add('btn-success');

        setTimeout(() => {
            btn.innerHTML = originalHTML;
            btn.classList.remove('btn-success');
            btn.classList.add('btn-info');
        }, 1500);

    }).catch(() => {
        alert('Gagal menyalin link');
    });
});
</script>

{{-- SweetAlert Delete --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-delete-visitor').forEach(button => {
    button.addEventListener('click', function () {
      const form = this.closest('.delete-visitor-form');

      Swal.fire({
        title: 'Hapus Visitor?',
        text: 'Data visitor tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal',
            customClass: {
                icon: 'mt-4'
            }
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'Menghapus...',
            allowOutsideClick: false,
            didOpen: () => {
              Swal.showLoading();
              form.submit();
            }
          });
        }
      });
    });
  });
});
</script>
@endsection
