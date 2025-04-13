<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\IdentityHelper;

class IdentityController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'identity_image' => 'required|image|max:5120',
        ]);

        $path = $request->file('identity_image')->store('temp-uploads');
        $fullPath = storage_path('app/' . $path);

        $text = IdentityHelper::scanIdentity($fullPath);

        if (strpos($text, 'Error:') === 0) {
            return response()->json(['success' => false, 'message' => $text]);
        }

        $data = IdentityHelper::extractDataFromText($text);

        if (empty($data)) {
            return response()->json(['success' => false, 'message' => 'No valid data extracted from the ID.']);
        }

        return response()->json([
            'success' => true,
            'name' => $data['name'] ?? '',
            'national_id' => $data['national_id'] ?? '',
            'birth_date' => $data['birth_date'] ?? '',
        ]);
    }
}
