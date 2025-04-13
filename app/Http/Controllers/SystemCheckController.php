<?php

namespace App\Http\Controllers;

class SystemCheckController extends Controller
{
    public function checkGoogleCredentials()
    {
        $path = storage_path('app/google-credentials.json');

        if (file_exists($path)) {
            $content = file_get_contents($path);
            return response()->json(json_decode($content, true));
        } else {
            return response()->json(['error' => 'Google credentials file not found.'], 404);
        }
    }
}
