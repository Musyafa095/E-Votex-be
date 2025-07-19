<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;


class CandidateController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:api', 'admin'])->except(['index', 'show']);
    }
    public function index(Request $request)
    {
        $query = Candidate::query();

        if ($request->has('search')) {
            $searching = $request->input('search');
            $query->where('name', "LIKE", "%$searching%");
        }

        $per_page = $request->input('per_page', 8);

        $allCandidate = $query->paginate($per_page);

        return response()->json([
            'message' => 'Candidate berhasil diTampilkan semua.',
            'data' => $allCandidate
        ], 200);
        
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'NIM' => 'required',
            'image' => "required|image|mimes:jpg,png,jpeg,gif,svg|max:2048",
            'visi' => 'required',
            'misi' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);
        $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath(), [
            'folder' => 'image',
        ])->getSecurePath();
            $Candidate = new Candidate();
            $Candidate->name = $request->input('name');
            $Candidate -> NIM = $request->input('NIM');
            $Candidate -> visi = $request->input('visi');
            $Candidate -> misi = $request->input('misi');
            $Candidate -> category_id = $request->input('category_id');
            $Candidate -> image = $uploadedFileUrl;
    
            $Candidate->save();
            return response()->json([
                'message' => 'Berhasil menambahkan candidate',
            ], 200);   
    }

    public function show($id)
    {
        $Candidate = Candidate::with('category', 'vote.user.profile')->find($id);
        if (!$Candidate){
            return response()->json([
                'message' => 'Data candidate tidak ditemukan'
            ], 404);    
        }
        return response()->json([
            'message' => 'Detail candiddate berhasil ditampilkan',
            'data' => $Candidate
        ], 200);
    }

    public function update(Request $request,  $id)
    {
        $request->validate([
            'name' => 'required',
            'NIM' => 'required',
            'image' => "required|image|mimes:jpg,png,jpeg,gif,svg|max:2048",
            'visi' => 'required',
            'misi' => 'required',
            'category_id' => 'required|exists:categories,id'
        ]);

        $Candidate = Candidate::find($id);
        if ($request->hasFile('image')) {
            $uploadedFileUrl = cloudinary()->upload($request->file('image')->getRealPath(), [
                'folder' => 'image',
            ])->getSecurePath();
            $Candidate -> image = $uploadedFileUrl;
        }
       
        if (!$Candidate) {
            return response()->json([
                'message' => 'candidate tidak ditemukan',
            ], 404);
        }
            
            $Candidate->name = $request->input('name');
            $Candidate -> NIM = $request->input('NIM');
            $Candidate -> visi = $request->input('visi');
            $Candidate -> misi = $request->input('misi');
            $Candidate -> category_id = $request->input('category_id');
            $Candidate -> image = $uploadedFileUrl;
    
            $Candidate->save();
            return response()->json([
                'message' => 'Berhasil mengupdate candidate',
            ], 200);
        
    }

    public function destroy($id)
    {
        $Candidate = Candidate::find($id);
        $Candidate->delete();
        return response()->json([
            'message' => 'Berhasil menghapus candidate'
        ], 200);
    }
}
