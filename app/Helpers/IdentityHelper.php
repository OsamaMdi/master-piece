<?php

namespace App\Helpers;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class IdentityHelper
{
    public static function extractDataFromText($text)
    {
        $data = [];

        if (preg_match('/(?:الاسم|Name)\s*[:\s]*(.+)/u', $text, $matches)) {
            $data['name'] = trim($matches[1]);
        }

        if (preg_match('/(?:الرقم الوطني|National ID)\s*[:\s]*(\d+)/u', $text, $matches)) {
            $data['national_id'] = trim($matches[1]);
        }

        if (preg_match('/(?:تاريخ الولادة|Date of Birth)\s*[:\s]*(\d{2}\/\d{2}\/\d{4})/u', $text, $matches)) {
            $data['birth_date'] = trim($matches[1]);
        }

        return $data;
    }

    public static function scanIdentity($imagePath)
    {
        try {
            if (!file_exists($imagePath)) {
                throw new \Exception("Image file not found.");
            }

            $client = new ImageAnnotatorClient([
                'credentials' => storage_path('app/google-credentials.json')
            ]);

            $imageContent = file_get_contents($imagePath);
            $response = $client->textDetection($imageContent);
            $texts = $response->getTextAnnotations();

            return $texts ? $texts[0]->getDescription() : "No text found.";
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
