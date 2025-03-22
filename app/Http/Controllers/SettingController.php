<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function accountView()
    {
        $settings = $this->getSettings();
        return view('pages.settings.general', compact('settings'));
    }

    public function securityView()
    {
        $settings = $this->getSettings();
        return view('pages.settings.security', compact('settings'));
    }

    public function securityLogsView()
    {
        // Implementasi tampilan log keamanan
        return view('pages.settings.security-logs');
    }

    public function updateGeneral(Request $request)
    {
        $request->validate([
            'institution_name' => 'required|string|max:255',
            'institution_code' => 'nullable|string|max:50',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'timezone' => 'nullable|string|max:50',
            'date_format' => 'nullable|string|max:20',
            'footer_text' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:png,ico|max:1024',
        ]);

        $settings = $this->getSettings();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            if ($settings->logo && Storage::exists($settings->logo)) {
                Storage::delete($settings->logo);
            }
            $logo = $request->file('logo')->store('settings', 'public');
            $settings->logo = 'storage/' . $logo;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            if ($settings->favicon && Storage::exists($settings->favicon)) {
                Storage::delete($settings->favicon);
            }
            $favicon = $request->file('favicon')->store('settings', 'public');
            $settings->favicon = 'storage/' . $favicon;
        }

        // Update other settings
        $settings->institution_name = $request->institution_name;
        $settings->institution_code = $request->institution_code;
        $settings->contact_email = $request->contact_email;
        $settings->contact_phone = $request->contact_phone;
        $settings->address = $request->address;
        $settings->timezone = $request->timezone;
        $settings->date_format = $request->date_format;
        $settings->footer_text = $request->footer_text;

        $settings->save();

        return redirect()->route('settings.general')
            ->with('success', 'Pengaturan umum berhasil disimpan.');
    }

    public function updatePasswordSecurity(Request $request)
    {
        $request->validate([
            'min_password_length' => 'required|integer|min:6|max:16',
            'password_expiry_days' => 'required|integer|min:0|max:365',
        ]);

        $settings = $this->getSettings();

        $settings->min_password_length = $request->min_password_length;
        $settings->require_special_char = $request->has('require_special_char');
        $settings->require_number = $request->has('require_number');
        $settings->require_uppercase = $request->has('require_uppercase');
        $settings->password_expiry_days = $request->password_expiry_days;

        $settings->save();

        return redirect()->route('settings.security')
            ->with('success', 'Pengaturan keamanan password berhasil disimpan.');
    }

    public function updateLoginSecurity(Request $request)
    {
        $request->validate([
            'max_login_attempts' => 'required|integer|min:3|max:10',
            'lockout_duration' => 'required|integer|min:5|max:1440',
            'session_lifetime' => 'required|integer|min:5|max:1440',
        ]);

        $settings = $this->getSettings();

        $settings->enable_2fa = $request->has('enable_2fa');
        $settings->max_login_attempts = $request->max_login_attempts;
        $settings->lockout_duration = $request->lockout_duration;
        $settings->session_lifetime = $request->session_lifetime;
        $settings->force_https = $request->has('force_https');

        $settings->save();

        return redirect()->route('settings.security')
            ->with('success', 'Pengaturan keamanan login berhasil disimpan.');
    }

    public function updateIdentificationSecurity(Request $request)
    {
        $settings = $this->getSettings();

        $settings->require_student_id = $request->has('require_student_id');
        $settings->enable_id_card_scan = $request->has('enable_id_card_scan');
        $settings->require_id_verification = $request->has('require_id_verification');

        $settings->save();

        return redirect()->route('settings.security')
            ->with('success', 'Pengaturan identifikasi berhasil disimpan.');
    }

    public function updateNotificationSettings(Request $request)
    {
        $request->validate([
            'return_reminder_hours' => 'required|integer|min:1|max:24',
        ]);

        $settings = $this->getSettings();

        $settings->enable_email_notifications = $request->has('enable_email_notifications');
        $settings->notify_admin_on_new_booking = $request->has('notify_admin_on_new_booking');
        $settings->notify_late_returns = $request->has('notify_late_returns');
        $settings->return_reminder_hours = $request->return_reminder_hours;

        $settings->save();

        return redirect()->route('settings.security')
            ->with('success', 'Pengaturan notifikasi berhasil disimpan.');
    }

    public function updateLogSettings(Request $request)
    {
        $request->validate([
            'log_retention_days' => 'required|integer|min:30|max:365',
        ]);

        $settings = $this->getSettings();

        $settings->log_retention_days = $request->log_retention_days;
        $settings->log_equipment_condition = $request->has('log_equipment_condition');
        $settings->require_return_notes = $request->has('require_return_notes');
        $settings->enable_detailed_audit_log = $request->has('enable_detailed_audit_log');

        $settings->save();

        return redirect()->route('settings.security')
            ->with('success', 'Pengaturan log berhasil disimpan.');
    }

    public function activityLogsView()
    {
        // Implementasi tampilan log aktivitas
        return view('pages.settings.activity-logs');
    }

    /**
     * Get or create settings record
     */
    private function getSettings()
    {
        return Setting::firstOrCreate(['id' => 1], [
            'institution_name' => 'Lab Management System',
            'institution_code' => 'LMS',
            'contact_email' => 'admin@example.com',
            'contact_phone' => '',
            'address' => '',
            'timezone' => 'Asia/Jakarta',
            'date_format' => 'd/m/Y',
            'footer_text' => '© ' . date('Y') . ' Lab Management System',
            'logo' => 'assets/img/logo.png',
            'favicon' => 'assets/img/favicon.png',
            'min_password_length' => 8,
            'require_special_char' => true,
            'require_number' => true,
            'require_uppercase' => true,
            'password_expiry_days' => 90,
            'enable_2fa' => false,
            'max_login_attempts' => 5,
            'lockout_duration' => 30,
            'session_lifetime' => 120,
            'force_https' => true,
        ]);
    }
}
