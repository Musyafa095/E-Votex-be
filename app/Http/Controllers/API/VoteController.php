<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Vote;

class VoteController extends Controller
{
    public function updateCreateVote(Request $request) {
        $request->validate([
            'vote' => 'required|integer|max:5|min:1',
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
        try {
            $vote = Vote::updateOrCreate(
                ['user_id' => $user->id, 'candidate_id' => $request->input('candidate_id')],
                ['vote' => $request->input('vote')]
            );
    
            return response()->json([
                'message' => 'Vote berhasil dibuat/diupdate',
                'data' => $vote,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan vote',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}