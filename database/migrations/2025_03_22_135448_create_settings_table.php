<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            // General Settings
            $table->string('institution_name');
            $table->string('institution_code')->nullable();
            $table->string('contact_email');
            $table->string('contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('timezone')->default('Asia/Jakarta');
            $table->string('date_format')->default('d/m/Y');
            $table->string('footer_text')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();

            // Password Security
            $table->integer('min_password_length')->default(8);
            $table->boolean('require_special_char')->default(true);
            $table->boolean('require_number')->default(true);
            $table->boolean('require_uppercase')->default(true);
            $table->integer('password_expiry_days')->default(90);

            // Login Security
            $table->boolean('enable_2fa')->default(false);
            $table->integer('max_login_attempts')->default(5);
            $table->integer('lockout_duration')->default(30);
            $table->integer('session_lifetime')->default(120);
            $table->boolean('force_https')->default(true);

            $table->timestamps();
        });

        Schema::table('settings', function (Blueprint $table) {
            // Fields for Lab RPL general
            $table->string('lab_name')->nullable()->after('institution_name');
            $table->string('lab_code')->nullable()->after('institution_code');
            $table->string('lab_location')->nullable()->after('address');
            $table->text('lab_description')->nullable()->after('lab_location');
            $table->time('operation_start')->default('08:00')->nullable()->after('date_format');
            $table->time('operation_end')->default('16:00')->nullable()->after('operation_start');
            $table->integer('default_loan_duration')->default(2)->nullable()->after('operation_end');
            $table->integer('max_booking_days_ahead')->default(14)->nullable()->after('default_loan_duration');
            $table->float('late_return_limit')->default(1)->nullable()->after('max_booking_days_ahead');
            $table->boolean('require_approval')->default(true)->after('late_return_limit');
            $table->boolean('enable_weekend_bookings')->default(false)->after('require_approval');

            // Fields for security lab RPL
            $table->boolean('student_can_borrow_equipment')->default(false)->after('require_uppercase');
            $table->boolean('student_can_book_lab')->default(false)->after('student_can_borrow_equipment');
            $table->boolean('require_student_id')->default(true)->after('force_https');
            $table->boolean('enable_id_card_scan')->default(false)->after('require_student_id');
            $table->boolean('require_id_verification')->default(true)->after('enable_id_card_scan');
            $table->boolean('enable_email_notifications')->default(true)->after('require_id_verification');
            $table->boolean('notify_admin_on_new_booking')->default(true)->after('enable_email_notifications');
            $table->boolean('notify_late_returns')->default(true)->after('notify_admin_on_new_booking');
            $table->integer('return_reminder_hours')->default(2)->after('notify_late_returns');
            $table->integer('log_retention_days')->default(90)->after('return_reminder_hours');
            $table->boolean('log_equipment_condition')->default(true)->after('log_retention_days');
            $table->boolean('require_return_notes')->default(false)->after('log_equipment_condition');
            $table->boolean('enable_detailed_audit_log')->default(true)->after('require_return_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');

        Schema::table('settings', function (Blueprint $table) {
            // Remove fields for Lab RPL general
            $table->dropColumn('lab_name');
            $table->dropColumn('lab_code');
            $table->dropColumn('lab_location');
            $table->dropColumn('lab_description');
            $table->dropColumn('operation_start');
            $table->dropColumn('operation_end');
            $table->dropColumn('default_loan_duration');
            $table->dropColumn('max_booking_days_ahead');
            $table->dropColumn('late_return_limit');
            $table->dropColumn('require_approval');
            $table->dropColumn('enable_weekend_bookings');

            // Remove fields for security lab RPL
            $table->dropColumn('student_can_borrow_equipment');
            $table->dropColumn('student_can_book_lab');
            $table->dropColumn('require_student_id');
            $table->dropColumn('enable_id_card_scan');
            $table->dropColumn('require_id_verification');
            $table->dropColumn('enable_email_notifications');
            $table->dropColumn('notify_admin_on_new_booking');
            $table->dropColumn('notify_late_returns');
            $table->dropColumn('return_reminder_hours');
            $table->dropColumn('log_retention_days');
            $table->dropColumn('log_equipment_condition');
            $table->dropColumn('require_return_notes');
            $table->dropColumn('enable_detailed_audit_log');
        });
    }
};
