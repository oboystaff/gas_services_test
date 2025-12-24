<?php

namespace App\Http\Controllers\API\Community;

use App\Http\Controllers\Controller;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        $data = Community::orderBy('created_at', 'DESC')->get();

        return response()->json([
            'message' => 'Get all communities',
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $community = Community::query()
            ->where('id', $id)
            ->first();

        if (empty($community)) {
            return response()->json([
                'message' => 'Community not found'
            ], 422);
        }

        return response()->json([
            'message' => 'Get particular community',
            'data' => $community
        ]);
    }
}
