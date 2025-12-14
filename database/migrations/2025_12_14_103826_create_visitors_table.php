<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();

            // Relasi (opsional, sesuaikan project)
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            // Identitas visitor
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('label')->nullable();
            $table->string('group')->nullable();

            // Tracking teknis
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('device')->nullable();     // mobile / desktop / tablet
            $table->string('platform')->nullable();   // android / ios / windows / mac
            $table->string('browser')->nullable();    // chrome / safari / firefox

            // Lokasi (optional, dari IP)
            $table->string('country')->nullable();
            $table->string('city')->nullable();

            // Aktivitas visitor
            $table->string('page')->nullable();        // halaman yang dikunjungi
            $table->string('referrer')->nullable();   // dari mana datang
            $table->boolean('is_unique')->default(false);
            $table->unsignedInteger('visit_count')->default(1);

            // Interaksi event
            $table->enum('attendance_status', ['pending', 'hadir', 'tidak_hadir'])
                  ->default('pending');
            $table->timestamp('first_visit_at')->nullable();
            $table->timestamp('last_visit_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};

