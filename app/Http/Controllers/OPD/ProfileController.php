<?php

namespace App\Http\Controllers\OPD;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('opd.profile.edit', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password_lama' => 'required',
            'password_baru' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&]/'
            ]
        ]);

        // Cek password lama
        if (!Hash::check($request->password_lama, $user->password)) {
            return back();
        }

        // Update password langsung di database
        $user->password = Hash::make($request->password_baru);
        $user->save();

        // Tidak logout, user tetap login dengan session yang sama

        return back();
    }
}