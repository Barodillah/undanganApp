<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $events = Event::query()
            ->when($user->role_id == 3, function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        if (auth()->user()->role_id == 2) {
            abort(403);
        }

        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->role_id == 2) {
            abort(403);
        }

        $request->validate([
            'title'      => 'required|string|max:255',
            'event_date' => 'required|date',
            'status'     => 'required',
        ]);

        Event::create([
            'user_id'     => auth()->id(),
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dibuat');
    }

    public function edit(Event $event)
    {
        $user = auth()->user();

        // Role 2 dilarang
        if ($user->role_id == 2) {
            abort(403);
        }

        // Role 3 hanya boleh edit miliknya
        if ($user->role_id == 3 && $event->user_id !== $user->id) {
            abort(403);
        }

        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $user = auth()->user();

        if ($user->role_id == 2) {
            abort(403);
        }

        if ($user->role_id == 3 && $event->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'title'      => 'required|string|max:255',
            'event_date' => 'required|date',
            'status'     => 'required',
        ]);

        $event->update([
            'title'       => $request->title,
            'description' => $request->description,
            'event_date'  => $request->event_date,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil diperbarui');
    }

    public function destroy(Event $event)
    {
        $user = auth()->user();

        // Role 2 tidak boleh hapus
        if ($user->role_id == 2) {
            abort(403);
        }

        // Role 3 hanya boleh hapus miliknya (jika diizinkan)
        if ($user->role_id == 3 && $event->user_id !== $user->id) {
            abort(403);
        }

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Event berhasil dihapus');
    }

    public function show(Event $event)
    {
        $user = auth()->user();

        // Role 3 hanya boleh melihat event miliknya sendiri
        if ($user->role_id == 3 && $event->user_id != $user->id) {
            abort(403, 'Anda tidak memiliki akses ke event ini.');
        }

        // Role 1 & 2 otomatis lolos
        return view('admin.events.show', compact('event'));
    }

}
