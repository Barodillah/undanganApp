<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Visitor;
use App\Models\Event;

class VisitorSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 1 event (wajib ada)
        $event = Event::first();

        if (!$event) {
            $this->command->error('Event belum ada. Jalankan EventSeeder dulu.');
            return;
        }

        // 🔥 INI TEMPATNYA
        Visitor::factory()
            ->count(50)
            ->for($event)
            ->create();
    }
}
