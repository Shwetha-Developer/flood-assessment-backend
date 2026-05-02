<?php
namespace App\Http\Controllers;

use App\Http\Requests\AssessmentRequest;
use App\Http\Requests\BatchSyncRequest; // ← import
use App\Models\Assessment;              // ← import
use Carbon\Carbon;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'supervisor') {
            $assessments = Assessment::with(['user', 'photos'])
                ->latest()
                ->get();
        } else {
            $assessments = Assessment::with(['photos'])
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return response()->json([
            'success' => true,
            'data'    => $assessments,
        ]);
    }

    public function store(AssessmentRequest $request)
    {
        $existing = Assessment::where('local_id', $request->local_id)->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => 'Already synced',
                'data'    => $existing,
            ]);
        }

        // ← Convert date
        $assessedAt = null;
        if ($request->assessed_at) {
            try {
                $assessedAt = Carbon::parse($request->assessed_at)->format('Y-m-d H:i:s');
            } catch (\Exception $e) {
                $assessedAt = null;
            }
        }

        $assessment = Assessment::create([
            'local_id'       => $request->local_id,
            'user_id'        => $request->user()->id,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
            'address'        => $request->address,
            'condition'      => $request->condition,
            'total_chickens' => $request->total_chickens,
            'notes'          => $request->notes,
            'assessed_at'    => $assessedAt,
            'synced_at'      => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Assessment saved',
            'data'    => $assessment,
        ], 201);
    }

    public function show($id)
    {
        $assessment = Assessment::with(['user', 'photos'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $assessment,
        ]);
    }

    public function batchSync(BatchSyncRequest $request)
    {
        $results = [];

        foreach ($request->assessments as $data) {

            $existing = Assessment::where('local_id', $data['local_id'])->first();

            if ($existing) {
                $results[] = [
                    'local_id'  => $data['local_id'],
                    'server_id' => $existing->id,
                    'status'    => 'already_exists',
                ];
                continue;
            }

            // ← Convert ISO date to MySQL format
            $assessedAt = null;
            if (! empty($data['assessed_at'])) {
                try {
                    $assessedAt = Carbon::parse($data['assessed_at'])->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $assessedAt = null;
                }
            }

            $assessment = Assessment::create([
                'local_id'       => $data['local_id'],
                'user_id'        => $request->user()->id,
                'latitude'       => $data['latitude'],
                'longitude'      => $data['longitude'],
                'address'        => $data['address'],
                'condition'      => $data['condition'],
                'total_chickens' => $data['total_chickens'],
                'notes'          => $data['notes'] ?? null,
                'assessed_at'    => $assessedAt, // ← use converted date
                'synced_at'      => Carbon::now(),
            ]);

            $results[] = [
                'local_id'  => $data['local_id'],
                'server_id' => $assessment->id,
                'status'    => 'synced',
            ];
        }

        return response()->json([
            'success' => true,
            'results' => $results,
        ]);
    }
}
