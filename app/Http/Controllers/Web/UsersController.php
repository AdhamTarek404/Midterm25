<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function register(Request $request)
    {
        return view('users.register');
    }

    public function doRegister(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'min:3', 'max:128'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // 🔴 IMPORTANT: Generic error to prevent user enumeration
        if (User::where('email', $request->email)->first()) {
            return redirect()->route('register')
                ->withErrors(['email' => 'Registration failed. Please try again.']);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password); // 🔴 IMPORTANT: bcrypt hashing
        $user->role = 'member'; // 🔴 IMPORTANT: Default role = member (Requirement #2)
        $user->save();

        return redirect()->route('login')->with('success', 'Registration successful! Please login.');
    }

    public function login(Request $request)
    {
        return view('users.login');
    }

    public function doLogin(Request $request)
    {
        $this->validate($request, [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 🔴 IMPORTANT: Generic error message - no info leakage
        // ⚠️ COMMON MISTAKE: Saying "Email not found" or "Wrong password" separately
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->withErrors(['login' => 'Invalid email or password']);
        }

        $user = User::where('email', $request->email)->first();
        Auth::setUser($user);

        return redirect('/');
    }

    public function doLogout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function profile(Request $request)
    {
        $user = auth()->user();
        $borrows = $user->borrows()->with('book')->get();
        $borrowLimit = 3;
        $borrowCount = $borrows->count();

        return view('users.profile', compact('user', 'borrows', 'borrowLimit', 'borrowCount'));
    }

    public function members(Request $request)
    {
        $members = User::all();
        return view('users.members', compact('members'));
    }

    public function createLibrarian(Request $request)
    {
        return view('users.create-librarian');
    }

    public function storeLibrarian(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'min:3', 'max:128'],
            'email' => ['required', 'email', 'unique:users', 'max:255'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 'librarian';
        $user->save();

        return redirect()->route('members')->with('success', 'Librarian created successfully!');
    }

    public function roles(Request $request)
    {
        $users = User::all();
        $rolesInfo = [
            'admin' => ['Manage all users', 'Create librarians', 'Add/Edit/Delete books', 'View all members', 'View roles page'],
            'librarian' => ['Add/Edit/Delete books', 'View all members'],
            'member' => ['View books', 'Borrow books', 'View own profile', 'View borrow history'],
        ];
        return view('users.roles', compact('users', 'rolesInfo'));
    }
}
