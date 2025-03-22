<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'institution_name',
        'institution_code',
        'contact_email',
        'contact_phone',
        'address',
        'timezone',
        'date_format',
        'footer_text',
        'logo',
        'favicon',
        'min_password_length',
        'require_special_char',
        'require_number',
        'require_uppercase',
        'password_expiry_days',
        'enable_2fa',
        'max_login_attempts',
        'lockout_duration',
        'session_lifetime',
        'force_https',
        'require_student_id',
        'enable_id_card_scan',
        'require_id_verification',
        'enable_email_notifications',
        'notify_admin_on_new_booking',
        'notify_late_returns',
        'return_reminder_hours',
        'log_retention_days',
        'log_equipment_condition',
        'require_return_notes',
        'enable_detailed_audit_log',
        'student_can_borrow_equipment',
        'student_can_book_lab'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'require_special_char' => 'boolean',
        'require_number' => 'boolean',
        'require_uppercase' => 'boolean',
        'enable_2fa' => 'boolean',
        'force_https' => 'boolean',
        'require_student_id' => 'boolean',
        'enable_id_card_scan' => 'boolean',
        'require_id_verification' => 'boolean',
        'enable_email_notifications' => 'boolean',
        'notify_admin_on_new_booking' => 'boolean',
        'notify_late_returns' => 'boolean',
        'log_equipment_condition' => 'boolean',
        'require_return_notes' => 'boolean',
        'enable_detailed_audit_log' => 'boolean',
        'student_can_borrow_equipment' => 'boolean',
        'student_can_book_lab' => 'boolean'
    ];
}
