<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Event;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    public function index()
    {
        return view('checkin.index');
    }

    public function verify(Request $request)
    {
        $hash = $request->get('code');
        $slugEvent = $request->get('acara');

        if (!$hash) {
            return response()->json([
                'valid' => false,
                'message' => 'BARCODE TIDAK SESUAI'
            ]);
        }

        // Query visitor + event
        $query = Visitor::with('event');

        if ($slugEvent) {
            $query->whereHas('event', function ($q) use ($slugEvent) {
                $q->where('slug', $slugEvent);
            });
        }

        $visitors = $query->get();

        // Event tidak ditemukan
        if ($slugEvent && $visitors->isEmpty()) {
            return response()->json([
                'valid' => false,
                'message' => 'ACARA TIDAK DITEMUKAN'
            ]);
        }

        foreach ($visitors as $visitor) {

            // ===== GENERATE KODE ASLI =====
            $namaPrefix = strtoupper(
                substr(preg_replace('/[^A-Za-z]/', '', $visitor->name), 0, 3)
            );

            $kodeAsli =
                $namaPrefix . '-' .
                str_pad($visitor->event_id, 3, '0', STR_PAD_LEFT) . '-' .
                str_pad($visitor->id, 3, '0', STR_PAD_LEFT);

            $hashDb = hash('sha256', $kodeAsli);

            // ===== QR COCOK =====
            if ($hash === $hashDb) {

                // SUDAH HADIR
                if ($visitor->attendance_status === 'hadir') {
                    $visitor->update([
                        'last_visit_at' => now('Asia/Jakarta'),
                    ]);

                    return response()->json([
                        'valid' => true,
                        'message' => 'UNDANGAN SUDAH HADIR',
                        'nama_acara' => $visitor->event->title,
                        'nama_visitor' => $visitor->name,
                        'alamat' => $visitor->address,
                        'hadir_jam' => $visitor->first_visit_at
                    ]);
                }

                // UPDATE HADIR
                $visitor->update([
                    'attendance_status' => 'hadir',
                    'first_visit_at' => now('Asia/Jakarta'),
                    'last_visit_at' => now('Asia/Jakarta'),
                ]);

                return response()->json([
                    'valid' => true,
                    'message' => 'BARCODE SESUAI - HADIR DICATAT',
                    'nama_acara' => $visitor->event->title,
                    'nama_visitor' => $visitor->name,
                    'alamat' => $visitor->address
                ]);
            }
        }

        // TIDAK DITEMUKAN
        return response()->json([
            'valid' => false,
            'message' => 'BARCODE TIDAK SESUAI'
        ]);
    }
}
