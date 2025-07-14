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
        // Validiere den Input, um Directory Traversal Angriffe zu verhindern.
        // Das ist SEHR WICHTIG, da der Input direkt in einen Dateipfad eingefügt wird.
        $requestedAsset = $request->input('path');
        if (!$requestedAsset || Str::contains($requestedAsset, '..')) {
            abort(400, 'Invalid Path');
        }

        try {
            // Baue den Pfad zum 'public/assets' Verzeichnis innerhalb des Pakets
            $basePath = dirname(__DIR__, 3) . '/public/assets/';
            
            // Hänge den angeforderten und bereinigten Asset-Pfad an
            $path = $basePath . urldecode($requestedAsset);

        } catch (\LogicException $e) {
            // Dieser Catch-Block ist unwahrscheinlich, aber sicher ist sicher
            abort(404);
        }

        if (File::exists($path)) {
            $mime = '';
            if (Str::endsWith($path, '.js')) {
                $mime = 'text/javascript';
            } elseif (Str::endsWith($path, '.css')) {
                $mime = 'text/css';
            } else {
                $mime = File::mimeType($path);
            }
            $response = response(File::get($path), 200, ['Content-Type' => $mime]);
            $response->setSharedMaxAge(31536000);
            $response->setMaxAge(31536000);
            $response->setExpires(new \DateTime('+1 year'));
            
            // Die header() Funktion ist hier nicht ideal, da sie global ist.
            // Besser ist es, die Header direkt auf dem Response-Objekt zu setzen.
            // Laravel macht das meiste davon aber schon automatisch (wie ETag).
            // $response->header('Last-Modified', gmdate('D, d M Y H:i:s', filemtime($path)).' GMT');

            return $response;
        }

        return response('', 404);
    }
}