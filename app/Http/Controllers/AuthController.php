<?php


namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // ─── Register ───────────────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([

            'name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'regex:/^[a-zA-Z][a-zA-Z\s]{1,49}$/',   // letters + spaces only, starts with letter
            ],

            'email' => [
                'required',
                'string',
                'email:rfc,dns',   // rfc = format check, dns = domain must exist
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',           // must match password_confirmation
                Password::min(8)
                    ->mixedCase()      // requires upper + lower case
                    ->numbers()        // requires at least one number
                    ->symbols()        // requires at least one special character
                    ->uncompromised(), // checks against known breached passwords DB
            ],

            'default_city' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[a-zA-Z\s\-]+$/',   // letters, spaces, hyphens only
            ],

        ], [
            // ── Custom Error Messages ─────────────────────────────────
            'name.required'        => 'Full name is required.',
            'name.min'             => 'Name must be at least 3 characters long.',
            'name.max'             => 'Name cannot exceed 50 characters.',
            'name.regex'           => 'Name must contain only letters and spaces, and must start with a letter. Numbers and symbols are not allowed.',

            'email.required'       => 'Email address is required.',
            'email.email'          => 'Please enter a valid email address (e.g. you@example.com).',
            'email.unique'         => 'This email address is already registered. Please login instead.',
            'email.dns'            => 'The email domain does not appear to exist. Please use a valid email.',

            'password.required'    => 'Password is required.',
            'password.confirmed'   => 'Password and confirm password do not match.',
            'password.min'         => 'Password must be at least 8 characters long.',
            'password.mixed_case'  => 'Password must contain at least one uppercase and one lowercase letter.',
            'password.numbers'     => 'Password must contain at least one number (0–9).',
            'password.symbols'     => 'Password must contain at least one special character (e.g. @, $, !, #).',
            'password.uncompromised' => 'This password has appeared in a data breach. Please choose a safer password.',

            'default_city.regex'   => 'City name must contain only letters, spaces, or hyphens.',
            'default_city.max'     => 'City name cannot exceed 100 characters.',
        ]);

        // Clean up name — trim whitespace and title-case it
        $cleanName = ucwords(strtolower(trim($request->name)));

        $user = User::create([
            'name'         => $cleanName,
            'email'        => strtolower(trim($request->email)),
            'password'     => Hash::make($request->password),
            'default_city' => $request->default_city
                                ? ucwords(strtolower(trim($request->default_city)))
                                : 'London',
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', "Welcome, {$user->name}! Your account has been created.");
    }

    // ─── Login ───────────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'These credentials do not match our records.']);
    }

    // ─── Logout ──────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}