<?php

namespace App\Helpers;

class UserHelper
{
    /**
     * Format role from user object
     * 
     * @param mixed $user User object or role data
     * @param string $default Default value if role not found
     * @return string
     */
    public static function formatRole($user, $default = 'Pengguna')
    {
        // Jika tidak ada user
        if (!$user) {
            return $default;
        }

        // Periksa property role di model User
        if (isset($user->role)) {
            return self::parseAndFormatRole($user->role);
        }

        // Jika ada relasi roles
        if (isset($user->roles) && count($user->roles) > 0) {
            $role = $user->roles[0] ?? null;
            if ($role) {
                return isset($role->name) ? self::formatRoleName($role->name) : self::parseAndFormatRole($role);
            }
        }

        return $default;
    }

    /**
     * Parse and format role data
     * 
     * @param mixed $role
     * @return string
     */
    public static function parseAndFormatRole($role)
    {
        // Handle JSON string
        if (is_string($role) && self::isJson($role)) {
            $roleData = json_decode($role, true);

            // Format: {"id": 1, "name": "admin"}
            if (isset($roleData['name'])) {
                return self::formatRoleName($roleData['name']);
            }

            // Format: {"id": 1, "name": "admin", "description": null}
            if (isset($roleData['id']) && isset($roleData['name'])) {
                return self::formatRoleName($roleData['name']);
            }

            // Jika JSON array
            if (is_array($roleData)) {
                return self::formatRoleName(json_encode($roleData));
            }
        }

        // Handle array langsung
        if (is_array($role)) {
            if (isset($role['name'])) {
                return self::formatRoleName($role['name']);
            }

            // Convert array to string untuk kejelasan
            return self::formatRoleName(json_encode($role));
        }

        // Handle string standar
        return self::formatRoleName($role);
    }

    /**
     * Format role name to be more readable
     * 
     * @param string $roleName
     * @return string
     */
    public static function formatRoleName($roleName)
    {
        // Jika kosong, kembalikan default
        if (empty($roleName)) {
            return 'Pengguna';
        }

        // Map nama role ke nama yang lebih user-friendly
        $roleMap = [
            'admin' => 'Administrator',
            'superadmin' => 'Super Administrator',
            'teacher' => 'Guru',
            'student' => 'Siswa',
            'staff' => 'Staf',
            'lab_assistant' => 'Asisten Lab',
            'guest' => 'Tamu'
        ];

        // Untuk handle case yang berbeda
        $lowerRoleName = strtolower($roleName);

        // Returnkan dari map jika ada, atau kapitalisasi original
        return $roleMap[$lowerRoleName] ?? ucfirst($roleName);
    }

    /**
     * Check if a string is a valid JSON
     * 
     * @param string $string
     * @return bool
     */
    private static function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }

        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}