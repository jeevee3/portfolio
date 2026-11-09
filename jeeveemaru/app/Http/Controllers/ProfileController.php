<?php

namespace App\Http\Controllers;

use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $artworks = Artwork::where('type', 'portfolio')->latest()->get();
        $photos   = Artwork::where('type', 'photos')->latest()->get();

        return view('profile', compact('artworks', 'photos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'type'  => 'required|in:portfolio,photos',
        ]);

        $path = $request->file('image')->store('artworks', 'public');
        Artwork::create(['image_path' => $path, 'type' => $request->type]);

        return redirect(route('profile') . '#' . $request->type)
            ->with('success', 'Uploaded!')
            ->with('type', $request->type);
    }

    public function destroy(Artwork $artwork)
    {
        $type = $artwork->type;

        Storage::disk('public')->delete($artwork->image_path);
        $artwork->delete();

        return redirect(route('profile') . '#' . $type);
    }
}