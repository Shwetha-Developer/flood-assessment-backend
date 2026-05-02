<?php
namespace App\Http\Controllers;

use App\Models\Assessment;

class ExportController extends Controller
{
    // Export as CSV
    public function csv()
    {
        $assessments = Assessment::with(['user', 'photos'])->get();

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=assessments.csv',
        ];

        $callback = function () use ($assessments) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Assessor',
                'Address',
                'Latitude',
                'Longitude',
                'Condition',
                'Total Chickens',
                'Notes',
                'Assessed At',
                'Synced At',
            ]);

            // CSV Rows
            foreach ($assessments as $a) {
                fputcsv($file, [
                    $a->id,
                    $a->user->name ?? 'N/A',
                    $a->address,
                    $a->latitude,
                    $a->longitude,
                    $a->condition,
                    $a->total_chickens,
                    $a->notes,
                    $a->assessed_at,
                    $a->synced_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
