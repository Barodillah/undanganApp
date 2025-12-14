<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Event;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function index()
    {
        $visitors = Visitor::with('event')
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.visitors.index', compact('visitors'));
    }

    public function create(Request $request)
    {
        $events = Event::orderBy('title')->get();
        $selectedEvent = null;

        if ($request->filled('event_id')) {
            $selectedEvent = Event::find($request->event_id);
        }

        return view('admin.visitors.create', compact('events', 'selectedEvent'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name'     => 'required|string|max:100',
            'address'  => 'nullable|string',
            'attendance_status' => 'required'
        ]);

        $visitor = Visitor::create([
            'event_id' => $request->event_id,
            'name' => $request->name,
            'address' => $request->address,
            'attendance_status' => $request->attendance_status,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'first_visit_at' => now(),
            'last_visit_at' => now(),
        ]);

        // Ambil event dari visitor
        $event = $visitor->event;

        return redirect()
            ->route('visitors.byEvent', $event->slug)
            ->with('success', 'Visitor berhasil ditambahkan');
    }

    public function edit(Visitor $visitor)
    {
        $events = Event::orderBy('title')->get();
        return view('admin.visitors.edit', compact('visitor', 'events'));
    }

    public function update(Request $request, Visitor $visitor)
    {
        $request->validate([
            'event_id' => 'required',
            'name'     => 'required|string|max:100',
            'address'  => 'nullable|string',
            'attendance_status' => 'required'
        ]);

        $visitor->update($request->only([
            'event_id',
            'name',
            'address',
            'attendance_status'
        ]));

        return redirect()
            ->route('visitors.index')
            ->with('success', 'Visitor berhasil diperbarui');
    }

    public function destroy(Visitor $visitor)
    {
        $visitor->delete();

        return back()->with('success', 'Visitor berhasil dihapus');
    }

    public function byEvent(Event $event)
    {
        $visitors = Visitor::where('event_id', $event->id)
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.visitors.index', [
            'visitors' => $visitors,
            'event' => $event // penting
        ]);
    }
}
