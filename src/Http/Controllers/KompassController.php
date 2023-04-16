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
        try {
            $path = dirname(__DIR__, 3).'/public/assets/'.urldecode($request->path);
        } catch (\LogicException $e) {
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
            $response->header('Last-Modified', filemtime($path));

            return $response;
        }

        return response('', 404);
    }
}
