<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'hasFace' => is_array($user->FaceDescription) && count($user->FaceDescription) > 0,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Verify face descriptor against stored FaceDescription for current user.
     */
    public function verifyFace(Request $request): JsonResponse
    {
        $data = $request->validate([
            'descriptor'   => 'required|array',
            'descriptor.*' => 'numeric',
        ]);

        $user = $request->user();

        if (!$user->FaceDescription || !is_array($user->FaceDescription)) {
            return response()->json([
                'ok'      => false,
                'message' => 'Face is not registered for this account.',
            ], 422);
        }

        $stored = $user->FaceDescription;
        $probe  = $data['descriptor'];

        if (count($stored) !== count($probe)) {
            return response()->json([
                'ok'      => false,
                'message' => 'Descriptor length mismatch.',
            ], 422);
        }

        $sum = 0.0;
        $n   = count($stored);
        for ($i = 0; $i < $n; $i++) {
            $d   = $stored[$i] - $probe[$i];
            $sum += $d * $d;
        }
        $distance  = sqrt($sum);
        $threshold = 0.6;

        if ($distance > $threshold) {
            return response()->json([
                'ok'       => false,
                'message'  => 'Face verification failed.',
                'distance' => $distance,
            ], 403);
        }

        return response()->json([
            'ok'       => true,
            'message'  => 'Face verified.',
            'distance' => $distance,
        ]);
    }
}