<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Display the registration form.
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }    /**
     * Handle a registration request.
     * Note: Public registration is only allowed for patients.
     * Other user types (doctors, admin, lab staff, pharmacists) must be created by admin.
     */
    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'phone_number' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
        ]);

        // Public registration is only allowed for patients
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'gender' => $request->gender,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'user_type' => 'patient', // Force patient type for public registration
            'is_active' => true,
        ]);

        // Assign patient role
        $user->assignRole('patient');

        Auth::login($user);

        return redirect()->route('patient.dashboard')->with('success', 'Welcome to MediCare! Your patient account has been created successfully.');
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect user based on their role.
     */
    private function redirectBasedOnRole(User $user): RedirectResponse
    {
        switch ($user->user_type) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'doctor':
                return redirect()->route('doctor.dashboard');
            case 'patient':
                return redirect()->route('patient.dashboard');
            case 'laboratory_staff':
                return redirect()->route('laboratory.dashboard');
            case 'pharmacist':
                return redirect()->route('pharmacy.dashboard');
            default:
                return redirect()->route('welcome');
        }
    }

    /**
     * Get the dashboard route name for the given user.
     */
    public static function getDashboardRoute(?User $user = null): string
    {
        if (!$user) {
            $user = Auth::user();
        }
        
        if (!$user) {
            return '/';
        }

        switch ($user->user_type) {
            case 'admin':
                return route('admin.dashboard');
            case 'doctor':
                return route('doctor.dashboard');
            case 'patient':
                return route('patient.dashboard');
            case 'laboratory_staff':
                return route('laboratory.dashboard');
            case 'pharmacist':
                return route('pharmacy.dashboard');
            default:
                return '/';
        }
    }
}
