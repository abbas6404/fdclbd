<?php

namespace App\Http\Controllers\Guest\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login based on their role.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        
        if (!$user) {
            return route('login');
        }
        
        // Check if user has permission to access admin dashboard
        $adminPermission = Permission::where('name', 'system.dashboard')->first();
        
        if ($adminPermission) {
            // Check direct permissions
            $hasDirectPermission = DB::table('model_has_permissions')
                ->where('permission_id', $adminPermission->id)
                ->where('model_id', $user->id)
                ->where('model_type', get_class($user))
                ->exists();
                
            if ($hasDirectPermission) {
                return route('admin.dashboard');
            }
            
            // Check role permissions
            $permissionRoles = DB::table('role_has_permissions')
                ->where('permission_id', $adminPermission->id)
                ->pluck('role_id')
                ->toArray();
                
            if (!empty($permissionRoles)) {
                $hasRoleWithPermission = DB::table('model_has_roles')
                    ->where('model_id', $user->id)
                    ->where('model_type', get_class($user))
                    ->whereIn('role_id', $permissionRoles)
                    ->exists();
                    
                if ($hasRoleWithPermission) {
                    return route('admin.dashboard');
                }
            }
        }
        
        // All other users go to user dashboard
        return route('dashboard');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
        
        // Add rate limiting for login attempts
        $this->middleware('throttle:5,1')->only('login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Check if user account is locked due to too many failed attempts
        $this->checkAccountLockout($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of
        // attempts to login and redirect the user back to the login form. Of course,
        // when this user surpasses their maximum number of attempts they will
        // get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Check if the user account is locked due to too many failed attempts.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function checkAccountLockout(Request $request)
    {
        $login = $request->input('login');
        
        // Determine the field type for login
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (preg_match('/^[A-Z]+-\d+$/', $login)) {
            $field = 'code';
        } else {
            $field = 'phone';
        }
        
        $user = \App\Models\User::where($field, $login)->first();
        
        if ($user && $user->status === 'suspended') {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'login' => ['This account has been suspended due to security concerns.'],
            ]);
        }
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('guest.auth.login');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'login';
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $login = $request->input('login');
        
        // Determine the field type for login
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (preg_match('/^[A-Z]+-\d+$/', $login)) {
            // Check if it's a code format (e.g., SUPER-001, DR-001, AD-001)
            $field = 'code';
        } else {
            // Default to phone if not email or code
            $field = 'phone';
        }
        
        // Secure logging - only log the field type, not the actual value
        \Log::info('Login attempt', [
            'field_type' => $field,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);
        
        $request->merge([$field => $login]);
        
        return $this->guard()->attempt(
            [$field => $request->{$field}, 'password' => $request->password],
            $request->filled('remember')
        );
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        // Update last login time
        if (Auth::check()) {
            Auth::user()->updateLastLoginAt();
        }

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $login = $request->input('login');
        
        // Determine the field type for login
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        } elseif (preg_match('/^[A-Z]+-\d+$/', $login)) {
            $field = 'code';
        } else {
            $field = 'phone';
        }
        
        // Check if user exists with the given credentials
        $user = \App\Models\User::where($field, $login)->first();
        
        if (!$user) {
            // User doesn't exist
            $errorMessage = match($field) {
                'email' => 'No account found with this email address.',
                'code' => 'Invalid Staff ID. Please check your credentials.',
                'phone' => 'No account found with this phone number.',
                default => 'Invalid login credentials.'
            };
        } else {
            // User exists but password is wrong
            $errorMessage = 'The password you entered is incorrect.';
        }
        
        throw \Illuminate\Validation\ValidationException::withMessages([
            'login' => [$errorMessage],
        ]);
    }

    /**
     * Get the lockout response for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        throw \Illuminate\Validation\ValidationException::withMessages([
            'login' => ['Too many login attempts. Please try again in ' . ceil($seconds / 60) . ' minutes.'],
        ])->status(429);
    }
}
