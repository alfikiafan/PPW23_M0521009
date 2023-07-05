<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        if ($search) {
            $users = User::where('id', 'like', "%$search%")
                ->orWhere('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('role', 'like', "%$search%")
                ->orWhere('joining_date', 'like', "%$search%")
                ->paginate(8);
        } else {
            $users = User::paginate(8);
        }

        return view('accounts.index', compact('users'));
    }

    public function create(): View
    {
        return view('accounts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'photo' => 'required|image|mimes:jpg,jpeg,png',
            'password' => 'required|confirmed',
            'role' => 'required',
            'joining_date' => 'required|date',
        ]);
    
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('public/img/avatar');
            $filename = str_replace('public/', 'storage/', $path);
            $data['photo'] = $filename;
        }
    
        $data = $request->all();
        $data['photo'] = $filename;
    
        $success = User::create($data);
    
        if ($success) {
            return redirect()->route('accounts.index')->with('success', 'User added successfully.');
        } else {
            return redirect()->route('accounts.create')->withErrors('User failed to add.');
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()->route('accounts.index')->with('success', 'User deleted successfully.');
    }

    public function showProfile(User $user)
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function editProfile(User $user): View
    {
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'photo' => 'image|mimes:jpg,jpeg,png',
        ]);

        // Mengambil data dari request
        $data = $request->only(['name', 'email']);

        // Jika password baru tidak kosong, validasi password baru
        if (!empty($request->input('password'))) {
            $request->validate([
                'password' => 'required',
            ]);

            $data['password'] = bcrypt($request->input('password'));
        }

        if ($request->hasFile('photo')) {
            // Menghapus foto lama jika ada
            if ($user->photo) {
                Storage::delete($user->photo);
            }

            $path = $request->file('photo')->store('public/img/avatar');
            $filename = str_replace('public/', 'storage/', $path);
            $data['photo'] = $filename;
        }

        // Update data pengguna hanya jika validasi berhasil
        $success = $user->update($data);

        if ($success) {
            return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
        } else {
            return redirect()->route('profile.edit', $user->id)->withErrors('Profile failed to update.');
        }
    }
    
    function showDashboard(User $user)
    {
        $user = auth()->user();
        return view('sessions.index', compact('user'));
    }
}
