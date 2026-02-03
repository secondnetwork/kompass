<?php

namespace Secondnetwork\Kompass\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class KompassController extends Controller
{
    public function assets(Request $request)
    {
        $requestedAsset = $request->query('path');

        if (empty($requestedAsset)) {
            return response()->json(['error' => 'Path parameter is required'], 400);
        }

        if (Str::contains($requestedAsset, '..') || str_starts_with($requestedAsset, '/')) {
            abort(400, 'Invalid Path');
        }

        $basePath = dirname(__DIR__, 3).'/public/assets/';
        $decodedPath = urldecode($requestedAsset);
        $path = $basePath.$decodedPath;

        if (! File::exists($path)) {
            return response()->json(['error' => 'Asset not found', 'requested' => $decodedPath], 404);
        }

        $mime = match (true) {
            Str::endsWith($path, '.js') => 'text/javascript',
            Str::endsWith($path, '.css') => 'text/css',
            Str::endsWith($path, '.svg') => 'image/svg+xml',
            default => File::mimeType($path),
        };

        $response = response(File::get($path), 200, ['Content-Type' => $mime]);
        $response->setSharedMaxAge(31536000);
        $response->setMaxAge(31536000);
        $response->setExpires(new \DateTime('+1 year'));

        return $response;
    }
}
