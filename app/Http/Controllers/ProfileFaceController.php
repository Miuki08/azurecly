<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileFaceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'descriptor'   => 'required|array',
            'descriptor.*' => 'numeric',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->FaceDescription = $data['descriptor'];
        $user->save();

        return response()->json([
            'status'  => 'ok',
            'message' => 'Face descriptor berhasil disimpan.',
        ]);
    }
}