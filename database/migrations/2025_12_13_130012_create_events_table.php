<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Relasi pemilik event
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Identitas event
            $table->string('title');                 // Nama event
            $table->string('slug')->unique();        // URL event
            $table->string('type')->nullable();      // wedding, seminar, ulang tahun, dll

            // Deskripsi
            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            // Waktu event
            $table->date('event_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('timezone')->default('Asia/Jakarta');

            // Lokasi event
            $table->string('venue_name')->nullable();
            $table->text('venue_address')->nullable();
            $table->string('venue_city')->nullable();
            $table->string('venue_maps_link')->nullable();

            // Cover & media
            $table->string('cover_image')->nullable();
            $table->string('banner_image')->nullable();
            $table->json('gallery')->nullable();

            // Kapasitas & kehadiran
            $table->integer('max_guests')->nullable();
            $table->integer('guests_attending')->default(0);

            // RSVP
            $table->boolean('rsvp_enabled')->default(true);
            $table->date('rsvp_deadline')->nullable();

            // Status event
            $table->enum('status', [
                'draft',
                'published',
                'private',
                'cancelled',
                'finished'
            ])->default('draft');

            // Pengaturan tampilan
            $table->string('theme')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('font_family')->nullable();

            // SEO & share
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Statistik
            $table->unsignedInteger('views')->default(0);

            // Soft delete & timestamp
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
