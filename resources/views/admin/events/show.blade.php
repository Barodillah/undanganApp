@extends('admin.layouts.master')

@section('content')
@php
    $defaultCover = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSnBkzLPavFE0XSmYKY8ics1Q6uSbmUHoO1Q&s';

    $defaultBanner = 'https://t3.ftcdn.net/jpg/09/77/18/98/360_F_977189837_DyEnDwdywN7vGsu95zOpbprWnQT2gWL5.jpg';

    $defaultMap = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d6480.680842396705!2d106.80235251015783!3d-6.217471785607294!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f7dfbf106277%3A0xc053a9bed31a5ce3!2sIndonesia%20Arena!5e0!3m2!1sid!2sid!4v1765652035506!5m2!1sid!2sid';

    $fallbackGallery = [
        'https://images.unsplash.com/photo-1523438885200-e635ba2c371e',
        'https://images.unsplash.com/photo-1519741497674-611481863552',
        'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e',
        'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee',
    ];
@endphp

<div class="container-fluid">

    {{-- BANNER --}}
    <div class="card mb-4 position-relative overflow-hidden">
        <img
            src="{{ $event->banner_image ? asset('storage/'.$event->banner_image) : $defaultBanner }}"
            style="height:300px;object-fit:cover;"
            class="w-100"
        >

        {{-- Edit Banner --}}
        <a href="{{ route('events.edit', $event->id) }}"
           class="btn btn-sm btn-dark position-absolute"
           style="top:15px;right:15px;">
            <i class="fas fa-edit"></i> Edit Banner
        </a>
    </div>

    {{-- HEADER EVENT --}}
    <div class="row mb-4">
        <div class="col-md-4 text-center position-relative">
            <img
                src="{{ $event->cover_image ? asset('storage/'.$event->cover_image) : $defaultCover }}"
                class="img-fluid rounded shadow"
            >

            {{-- Edit Cover --}}
            <a href="{{ route('events.edit', $event->id) }}"
               class="btn btn-sm btn-dark position-absolute"
               style="bottom:15px;right:15px;">
                <i class="fas fa-edit"></i> Edit Cover
            </a>
        </div>

        <div class="col-md-8">
            <h2 class="fw-bold">{{ $event->title }}</h2>

            <span class="badge bg-info text-dark mb-2">
                {{ ucfirst($event->status) }}
            </span>

            <p class="text-muted mb-1">
                <i class="fas fa-calendar"></i>
                {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
            </p>

            <p class="text-muted mb-1">
                <i class="fas fa-clock"></i>
                {{ $event->start_time ?? '-' }} — {{ $event->end_time ?? '-' }}
                ({{ $event->timezone }})
            </p>

            <p class="text-muted">
                <i class="fas fa-map-marker-alt"></i>
                {{ $event->venue_name ?? '-' }},
                {{ $event->venue_city ?? '-' }}
            </p>

            <hr>

            <p>{{ $event->description ?? 'Tidak ada deskripsi event.' }}</p>
        </div>
    </div>

    {{-- DETAIL EVENT --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Informasi Acara</div>
                <div class="card-body">
                    <p><strong>Tipe:</strong> {{ $event->type ?? '-' }}</p>
                    <p><strong>Catatan:</strong> {{ $event->notes ?? '-' }}</p>
                    <p><strong>Maks. Tamu:</strong> {{ $event->max_guests ?? '-' }}</p>
                    <p><strong>Hadir:</strong> {{ $event->guests_attending }}</p>
                    <p>
                        <strong>RSVP:</strong>
                        <span class="badge bg-{{ $event->rsvp_enabled ? 'success' : 'secondary' }}">
                            {{ $event->rsvp_enabled ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </p>
                    <p><strong>Batas RSVP:</strong> {{ $event->rsvp_deadline ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- MAP --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header fw-bold">Lokasi Acara</div>
                <div class="card-body p-0">
                    <iframe
                        src="{{ $event->venue_maps_link ?? $defaultMap }}"
                        width="100%"
                        height="300"
                        style="border:0;"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    {{-- GALLERY --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Galeri</span>

            {{-- Add Gallery --}}
            <a href="{{ route('events.edit', $event->id) }}"
               class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Tambah Foto
            </a>
        </div>

        <div class="card-body">
            <div class="row">
                @php
                    $gallery = $event->gallery
                        ? json_decode($event->gallery, true)
                        : $fallbackGallery;
                @endphp

                @foreach($gallery as $img)
                    <div class="col-md-3 mb-3">
                        <img
                            src="{{ str_starts_with($img, 'http') ? $img : asset('storage/'.$img) }}"
                            class="img-fluid rounded shadow-sm"
                            style="height:180px;object-fit:cover;width:100%;"
                        >
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- FOOTER INFO --}}
    <div class="text-muted text-center small">
        Dibuat {{ $event->created_at->format('d M Y H:i') }} ·
        Terakhir update {{ $event->updated_at->format('d M Y H:i') }} ·
        Views {{ $event->views }}
    </div>

</div>
@endsection
