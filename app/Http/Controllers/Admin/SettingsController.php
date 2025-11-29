<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage settings');
    }
    
    /**
     * Display the settings page.
     */
    public function index()
    {
        $generalSettings = [
            'site_name' => config('app.name'),
            'site_description' => config('app.description', ''),
            'contact_email' => config('app.contact_email', 'contact@example.com'),
        ];
        
        $securitySettings = [
            'enable_registration' => config('app.enable_registration', true),
            'enable_social_login' => config('app.enable_social_login', false),
            'default_user_role' => config('app.default_role', 2), // Default to User role
            'login_attempts' => config('app.login_attempts', 5),
        ];
        
        $emailSettings = [
            'mail_driver' => config('mail.default'),
            'mail_host' => config('mail.mailers.smtp.host'),
            'mail_port' => config('mail.mailers.smtp.port'),
            'mail_username' => config('mail.mailers.smtp.username'),
            'mail_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_from_address' => config('mail.from.address'),
            'mail_from_name' => config('mail.from.name'),
        ];
        
        return view('admin.settings.index', compact('generalSettings', 'securitySettings', 'emailSettings'));
    }
    
    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:1000',
            'contact_email' => 'required|email',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        // Update the .env file with new values
        $this->updateEnvironmentFile([
            'APP_NAME' => '"' . $request->site_name . '"',
            'APP_DESCRIPTION' => '"' . $request->site_description . '"',
            'APP_CONTACT_EMAIL' => $request->contact_email,
        ]);
        
        // Handle logo upload if provided
        if ($request->hasFile('site_logo')) {
            $logoPath = $request->file('site_logo')->store('site', 'public');
            $this->updateEnvironmentFile(['APP_LOGO' => $logoPath]);
        }
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'General settings updated successfully');
    }
    
    /**
     * Update security settings.
     */
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'enable_registration' => 'boolean',
            'enable_social_login' => 'boolean',
            'default_user_role' => 'required|exists:roles,id',
            'login_attempts' => 'required|integer|min:3|max:10',
        ]);
        
        // Update the .env file with new values
        $this->updateEnvironmentFile([
            'APP_ENABLE_REGISTRATION' => $request->enable_registration ? 'true' : 'false',
            'APP_ENABLE_SOCIAL_LOGIN' => $request->enable_social_login ? 'true' : 'false',
            'APP_DEFAULT_ROLE' => $request->default_user_role,
            'APP_LOGIN_ATTEMPTS' => $request->login_attempts,
        ]);
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Security settings updated successfully');
    }
    
    /**
     * Update email settings.
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'mail_driver' => 'required|string',
            'mail_host' => 'required_if:mail_driver,smtp|nullable|string',
            'mail_port' => 'required_if:mail_driver,smtp|nullable|integer',
            'mail_username' => 'required_if:mail_driver,smtp|nullable|string',
            'mail_password' => 'nullable|string',
            'mail_encryption' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
        ]);
        
        // Update the .env file with new values
        $envUpdate = [
            'MAIL_MAILER' => $request->mail_driver,
            'MAIL_HOST' => $request->mail_host,
            'MAIL_PORT' => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
            'MAIL_FROM_NAME' => '"' . $request->mail_from_name . '"',
        ];
        
        // Only update password if provided
        if ($request->filled('mail_password')) {
            $envUpdate['MAIL_PASSWORD'] = $request->mail_password;
        }
        
        $this->updateEnvironmentFile($envUpdate);
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Email settings updated successfully');
    }
    
    /**
     * Send a test email.
     */
    public function sendTestEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);
        
        // Logic to send test email would go here
        // This is a placeholder for actual email sending code
        
        return redirect()->route('admin.settings.index')
            ->with('success', 'Test email sent successfully');
    }
    
    /**
     * Update the environment file with the given key-value pairs.
     *
     * @param array $data
     * @return void
     */
    protected function updateEnvironmentFile($data)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $content = file_get_contents($path);

            foreach ($data as $key => $value) {
                // If the key exists in the file
                if (strpos($content, $key . '=') !== false) {
                    // Replace the old value with the new one
                    $content = preg_replace('/' . $key . '=.*/', $key . '=' . $value, $content);
                } else {
                    // Add the key-value pair
                    $content .= "\n" . $key . '=' . $value;
                }
            }

            file_put_contents($path, $content);
        }
    }
} 