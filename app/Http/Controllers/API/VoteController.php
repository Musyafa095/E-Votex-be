<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;

class VoteController extends Controller
{
    public function updateCreateVote(Request $request) {
        $request->validate([
            'vote' => 'required|integer',
            'candidate_id' => 'required|exists:candidates,id'
        ], [
            'vote.required' => 'Vote wajib diisi',
            'vote.integer' => 'Vote harus berupa angka',
            'candidate_id.required' => 'ID kandidat wajib diisi',
            'candidate_id.exists' => 'Kandidat tidak ditemukan'
        ]);
    
        $user = auth()->user();
        if (!$user) {
            return response()->json([
                'message' => 'User tidak terautentikasi'
            ], 401);
        }

        $candidate = Candidate::find($request->input('candidate_id'));
        if (!$candidate) {
            return response()->json([
                'message' => 'Candidate tidak ditemukan'
            ], 404);
        }
    
        try {
            $candidate = Candidate::updateOrCreate(
                ['user_id' => $user->id, 'candidate_id' => $candidate->id],
                ['vote' => $request->input('vote')]
            );
    
            return response()->json([
                'message' => 'Vote berhasil dibuat/diupdate',
                'data' => $candidate,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan vote',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}