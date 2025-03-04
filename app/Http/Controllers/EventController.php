<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
{
    $events = Event::with('user')->orderBy('date', 'desc')->get();
    return response()->json([
        'message' => 'Events retrieved successfully',
        'data' => $events
    ]);
}


public function show($id)
{
    $event = Event::with(['user', 'category'])->findOrFail($id);
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
            'user_id' => 'required|exists:users,id',
            'host' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

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
            'image' => $imagePath
        ]);

        return response()->json([
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }


    public function update(Request $request, $id)
    {
        Log::info('Request Data:', $request->all());
    
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'location' => 'required|string|max:255',
            'status' => 'required|in:upcoming,completed,canceled',
            'category_id' => 'required|exists:categories,id',
            'host' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validatedData['image'] = $imagePath;
        }
    
        $event->update($validatedData);
        
        return response()->json(['message' => 'Event updated successfully', 'data' => $event]);
    }
    


    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        if (!$event) {
            return response()->json(['message' => 'NotFound'], 404);
        }

        // Delete image if exists
        if ($event->image) {
            Storage::delete('public/' . $event->image);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
