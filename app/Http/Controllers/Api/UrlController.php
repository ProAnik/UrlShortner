<?php

namespace App\Http\Controllers\Api;

use App\Models\Url;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UrlController extends Controller
{

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'original_url' => 'required|url'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Invalid URL'], 400);
            }

            $url = Url::create([
                'original_url' => $request->original_url,
                'short_url' => Url::generateShortUrl()
            ]);

            return response()->json(['short_url' => $url->short_url], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function retrieve($shortUrl)
    {
        try {
            $url = Url::where('short_url', $shortUrl)->pluck('original_url')->first();

            if (!$url) {
                return response()->json(['error' => 'URL not found'], 404);
            }
            return response()->json(['original_url' => $url], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }


}
