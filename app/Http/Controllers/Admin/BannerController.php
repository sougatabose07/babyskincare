<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();

        return view('admin.banners.index', compact('banners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => ['required', 'array'],
            'images.*' => ['required', 'image', 'max:4096'],
        ]);

        File::ensureDirectoryExists(public_path('images'));

        foreach ($request->file('images') as $image) {
            $filename = uniqid('banner_') . '.' . $image->extension();
            $image->move(public_path('images'), $filename);
            $path = 'images/' . $filename;

            Banner::create([
                'image' => $path,
                'sort_order' => Banner::max('sort_order') + 1,
                'active' => true,
            ]);
        }

        return redirect()->route('admin.banners.index')->with('success', 'Banner images uploaded successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image && File::exists(public_path($banner->image))) {
            File::delete(public_path($banner->image));
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner removed successfully.');
    }
}
