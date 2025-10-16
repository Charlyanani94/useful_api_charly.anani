<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShortLinkController extends Controller
{
    //
     public function shorten(Request $request)
    {
        $request->validate([
            'original_url' => 'required|url',
            'custom_code' => 'nullable|string|alpha_dash|max:10|unique:short_links,code',
        ]);

        $code = $request->custom_code ?? Str::random(6);

        $shortLink = ShortLink::create([
            'user_id' => Auth::id(),
            'original_url' => $request->original_url,
            'code' => $code,
        ]);

        return response()->json($shortLink, 201);
    }

    public function redirect($code)
    {
        $link = ShortLink::where('code', $code)->firstOrFail();
        $link->increment('clicks');
        return redirect($link->original_url);
    }

    public function index()
    {
        return response()->json(Auth::user()->shortLinks);
    }

    public function destroy(ShortLink $shortLink)
    {
        $this->authorize('delete', $shortLink);
        $shortLink->delete();
        return response()->json(['message' => 'Link deleted successfully']);
    }
}
