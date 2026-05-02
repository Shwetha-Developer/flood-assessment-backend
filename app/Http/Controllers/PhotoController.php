<?php
namespace App\Http\Controllers;

use App\Http\Requests\PhotoRequest;
use App\Models\Assessment;
use App\Models\Photo;

class PhotoController extends Controller
{
    public function store(PhotoRequest $request, $assessmentId)
    {
        $assessment = Assessment::findOrFail($assessmentId);
        $saved      = [];

        foreach ($request->photos as $photoData) {

            // Check duplicate
            $existing = Photo::where('local_id', $photoData['local_id'])->first();
            if ($existing) {
                $saved[] = ['id' => $existing->id, 'local_id' => $existing->local_id];
                continue;
            }

            // Save base64 directly to database
            $photo = Photo::create([
                'local_id'      => $photoData['local_id'],
                'assessment_id' => $assessment->id,
                'file_path'     => 'base64_stored',
                'base64_data'   => $photoData['base64'],
            ]);

            $saved[] = ['id' => $photo->id, 'local_id' => $photo->local_id];
        }

        return response()->json([
            'success' => true,
            'data'    => $saved,
        ]);
    }
}
