<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $resident = auth()->user()->resident;

        $activeReportsCount = $resident->reports()->where('status', 'aktif')->count();
        $finishedReportsCount = $resident->reports()->where('status', 'selesai')->count();

        return view('pages.app.profile', compact('activeReportsCount', 'finishedReportsCount'));
    }

    public function edit()
    {
        $user = auth()->user();
        $resident = $user->resident;

        return view('pages.app.profile-edit', compact('user', 'resident'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $resident = $user->resident;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'required|string|max:20',
            'avatar' => 'nullable|image|max:2048',
        ]);

        // update user
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        // upload avatar jika ada
        if ($request->hasFile('avatar')) {
            if ($resident->avatar && Storage::disk('public')->exists($resident->avatar)) {
                Storage::disk('public')->delete($resident->avatar);
            }

            $resident->avatar = $request->file('avatar')
                ->store('assets/avatar', 'public');
        }

        // update resident
        $resident->phone_number = $data['phone_number'];
        $resident->save();

        return redirect()
            ->route('profile')
            ->with('success', 'Profil berhasil diperbarui');
    }
}
