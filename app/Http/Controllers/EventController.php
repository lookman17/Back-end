<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('date', 'desc')->get();
        return response()->json([
            'message' => 'Events retrieved successfully',
            'data' => $events
        ]);
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        return response()->json([
            'message' => 'Event retrieved successfully',
            'data' => $event
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'status' => 'required|in:upcoming,completed,canceled',
            'category_id' => 'required|exists:categories,id',
            'user_id' => 'required|exists:users,id', // Validate that user_id exists in users table
            'host' => 'required|string|max:255', // Add validation for host
            'image' => 'nullable|string|max:255' // Add validation for image
        ]);

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'user_id' => $request->user_id,
            'status' => $request->status,
            'category_id' => $request->category_id,
            'host' => $request->host,
            'image' => $request->image // Save image
        ]);

        return response()->json([
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'status' => 'nullable|in:upcoming,completed,canceled',
            'category_id' => 'nullable|exists:categories,id',
            'host' => 'nullable|string|max:255',
            'image' => 'nullable|string|max:255' // Add validation for image
        ]);

        $event->update($request->only([
            'name', 'description', 'date', 'start_time', 'end_time', 'location', 'status', 'category_id', 'host', 'image' // Save image
        ]));

        return response()->json([
            'message' => 'Event updated successfully',
            'data' => $event
        ]);
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        // Cek apakah user yang login adalah pemilik event
        if ($event->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
