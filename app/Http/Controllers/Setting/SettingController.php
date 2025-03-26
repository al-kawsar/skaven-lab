<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Display the settings page with tabs
     */
    public function index()
    {
        $groups = Setting::select('group')->distinct()->pluck('group');
        $settings = [];

        // Organize settings by group
        foreach ($groups as $group) {
            $settings[$group] = Setting::where('group', $group)->get();
        }

        return view('pages.admin.settings.index', compact('settings', 'groups'));
    }

    /**
     * Show general settings view
     */
    public function generalView()
    {
        $settings = $this->getGroupSettings('institution');
        $systemSettings = $this->getGroupSettings('system');

        return view('pages.admin.settings.general', compact('settings', 'systemSettings'));
    }

    /**
     * Show security settings view
     */
    public function securityView()
    {
        $passwordSettings = $this->getGroupSettings('security_password');
        $loginSettings = $this->getGroupSettings('security_login');
        $identificationSettings = $this->getGroupSettings('security_identification');

        return view('pages.admin.settings.security', compact(
            'passwordSettings',
            'loginSettings',
            'identificationSettings'
        ));
    }

    /**
     * Show lab settings view
     */
    public function labView()
    {
        $labSettings = $this->getGroupSettings('lab');
        $labEquipmentSettings = $this->getGroupSettings('lab_equipment');

        return view('pages.admin.settings.lab', compact('labSettings', 'labEquipmentSettings'));
    }

    /**
     * Show notification settings view
     */
    public function notificationView()
    {
        $notificationSettings = $this->getGroupSettings('notification');
        $logSettings = $this->getGroupSettings('logs');

        return view('pages.admin.settings.notification', compact('notificationSettings', 'logSettings'));
    }

    /**
     * Show print settings view
     */
    public function printView()
    {
        $printSettings = $this->getGroupSettings('print');

        return view('pages.admin.settings.print', compact('printSettings'));
    }

    /**
     * Update any settings group
     */
    public function update(Request $request)
    {
        $settings = Setting::all();
        $rules = [];

        // Build validation rules based on setting types
        foreach ($settings as $setting) {
            $key = $setting->key;
            $rules[$key] = 'nullable';

            if ($setting->type == 'email') {
                $rules[$key] .= '|email';
            } elseif ($setting->type == 'number') {
                $rules[$key] .= '|numeric';
            } elseif ($setting->type == 'url') {
                $rules[$key] .= '|url';
            } elseif ($setting->type == 'file') {
                $rules[$key] = 'nullable|file|mimes:jpeg,png,jpg,gif,svg,ico|max:2048';
            }
        }

        // Validate request data
        $validated = $request->validate($rules);

        // Update settings
        foreach ($settings as $setting) {
            $key = $setting->key;

            if ($setting->type == 'file' && $request->hasFile($key)) {
                // Delete old file if exists
                if ($setting->value && Storage::exists('public/' . str_replace('storage/', '', $setting->value))) {
                    Storage::delete('public/' . str_replace('storage/', '', $setting->value));
                }

                // Handle file upload
                $file = $request->file($key);
                $filename = $key . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('settings', $filename, 'public');
                $setting->value = 'storage/settings/' . $filename;
            } elseif ($setting->type == 'boolean') {
                // Handle boolean values (checkboxes)
                $setting->value = $request->has($key) ? 'true' : 'false';
            } else {
                // Handle regular inputs
                $setting->value = $request->input($key, $setting->value);
            }

            $setting->save();
        }

        // Clear the settings cache
        $this->clearCache();

        // Determine redirect based on group
        $redirectRoute = 'admin.settings.index';
        if ($request->has('group')) {
            switch ($request->group) {
                case 'general':
                    $redirectRoute = 'admin.settings.general';
                    break;
                case 'security':
                    $redirectRoute = 'admin.settings.security';
                    break;
                case 'lab':
                    $redirectRoute = 'admin.settings.lab';
                    break;
                case 'notification':
                    $redirectRoute = 'admin.settings.notification';
                    break;
                case 'print':
                    $redirectRoute = 'admin.settings.print';
                    break;
            }
        }

        return redirect()->route($redirectRoute)
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Update general settings specifically
     */
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'institution_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
        ]);

        $this->updateSettingsByKeys($request, [
            'institution_name',
            'institution_code',
            'contact_email',
            'contact_phone',
            'address',
            'timezone',
            'date_format',
            'time_format',
            'footer_text',
        ]);

        // Handle file uploads
        if ($request->hasFile('logo')) {
            $this->updateFileSettings($request, 'logo');
        }

        if ($request->hasFile('favicon')) {
            $this->updateFileSettings($request, 'favicon');
        }

        $this->clearCache();

        return redirect()->route('admin.settings.general')
            ->with('success', 'Pengaturan umum berhasil disimpan.');
    }

    /**
     * Update security settings
     */
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'min_password_length' => 'nullable|integer|min:6|max:16',
            'max_login_attempts' => 'nullable|integer|min:3|max:10',
        ]);

        // Update password security
        $this->updateSettingsByKeys($request, [
            'min_password_length',
            'password_expiry_days',
        ]);

        // Update boolean settings
        $this->updateBooleanSettings($request, [
            'require_special_char',
            'require_number',
            'require_uppercase',
            'enable_2fa',
            'force_https',
        ]);

        // Update login security
        $this->updateSettingsByKeys($request, [
            'max_login_attempts',
            'lockout_duration',
            'session_lifetime',
        ]);

        $this->clearCache();

        return redirect()->route('admin.settings.security')
            ->with('success', 'Pengaturan keamanan berhasil disimpan.');
    }

    /**
     * Update lab settings
     */
    public function updateLab(Request $request)
    {
        $this->updateSettingsByKeys($request, [
            'lab_name',
            'lab_code',
            'lab_location',
            'lab_description',
            'operation_start',
            'operation_end',
            'max_booking_days_ahead',
        ]);

        $this->updateBooleanSettings($request, [
            'require_approval',
            'enable_weekend_bookings',
            'student_can_borrow_equipment',
            'student_can_book_lab',
        ]);

        $this->clearCache();

        return redirect()->route('admin.settings.lab')
            ->with('success', 'Pengaturan laboratorium berhasil disimpan.');
    }

    /**
     * Update notification settings
     */
    public function updateNotification(Request $request)
    {
        $this->updateSettingsByKeys($request, [
            'return_reminder_hours',
            'log_retention_days',
        ]);

        $this->updateBooleanSettings($request, [
            'enable_email_notifications',
            'notify_admin_on_new_booking',
            'notify_late_returns',
            'log_equipment_condition',
            'require_return_notes',
            'enable_detailed_audit_log',
        ]);

        $this->clearCache();

        return redirect()->route('admin.settings.notification')
            ->with('success', 'Pengaturan notifikasi berhasil disimpan.');
    }

    /**
     * Update print settings
     */
    public function updatePrint(Request $request)
    {
        $this->updateSettingsByKeys($request, [
            'school_name',
            'school_address',
            'school_postal_code',
            'school_phone',
            'school_email',
            'school_website',
            'lab_head_name',
            'lab_head_title',
            'lab_head_nip',
            'print_footer_text',
            'borrowing_terms',
        ]);

        $this->clearCache();

        return redirect()->route('admin.settings.print')
            ->with('success', 'Pengaturan cetak berhasil disimpan.');
    }

    /**
     * Get all settings as an array
     */
    public function getAllSettings()
    {
        return Setting::getAllSettings();
    }

    /**
     * Get settings for a specific group
     */
    private function getGroupSettings($group)
    {
        return Setting::where('group', $group)->get();
    }

    /**
     * Update multiple settings by keys
     */
    private function updateSettingsByKeys(Request $request, array $keys)
    {
        foreach ($keys as $key) {
            if ($request->has($key)) {
                $setting = Setting::where('key', $key)->first();
                if ($setting) {
                    $setting->value = $request->input($key);
                    $setting->save();
                }
            }
        }
    }

    /**
     * Update boolean settings
     */
    private function updateBooleanSettings(Request $request, array $keys)
    {
        foreach ($keys as $key) {
            $setting = Setting::where('key', $key)->first();
            if ($setting) {
                $setting->value = $request->has($key) ? 'true' : 'false';
                $setting->save();
            }
        }
    }

    /**
     * Update file settings
     */
    private function updateFileSettings(Request $request, $key)
    {
        $setting = Setting::where('key', $key)->first();
        if (!$setting)
            return;

        // Delete existing file if exists
        if ($setting->value && Storage::exists('public/' . str_replace('storage/', '', $setting->value))) {
            Storage::delete('public/' . str_replace('storage/', '', $setting->value));
        }

        // Upload new file
        $file = $request->file($key);
        $filename = $key . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('settings', $filename, 'public');
        $setting->value = 'storage/settings/' . $filename;
        $setting->save();
    }

    /**
     * Clear settings cache
     */
    private function clearCache()
    {
        Cache::forget('app_settings');
    }
}
