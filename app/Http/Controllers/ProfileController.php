<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller{
    public function edit(Request $request): View{
        $user = Auth::user();
        return view('profile.edit', [
            'user' => $user,
            'title' => 'Profil Saya'
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse{
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $user = Auth::user();
        $validated = $request->validated();
        if ($user->role === 'admin') {
            if (!empty($validated['password'])) {
                $user->update([
                    'password' => Hash::make($validated['password'])
                ]);
                return Redirect::route('profile.edit')->with('status', 'password-updated');
            }
            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } else {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->phone = $validated['phone'] ?? $user->phone;
            $user->address = $validated['address'] ?? $user->address;
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            $user->save();
        }
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse{
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return Redirect::to('/');
    }
}