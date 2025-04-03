<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
// use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// use Laravel\Scout\Searchable;
use App\Traits\HasRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRole, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    protected $hidden = [
        'password',
    ];

    const ROLE_SUPERADMIN = 1;
    const ROLE_ADMIN = 2;
    const ROLE_TEACHER = 3;
    const ROLE_STUDENT = 4;
    const ROLE_GUEST = 5;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string) str()->uuid(); // Menghasilkan UUID saat membuat model
        });
    }
    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function file()
    {
        return $this->hasOne(File::class, 'id');
    }

    /**
     * Get formatted role name for the user
     *
     * @param string $default Default role name if user has no roles
     * @return string
     */
    public function getFormattedRole($default = 'Pengguna')
    {
        // Jika menggunakan Spatie Permission Package
        if (method_exists($this, 'getRoleNames')) {
            $role = $this->getRoleNames()->first();

            if ($role) {
                return $this->formatRoleName($role);
            }
        }

        // Jika menggunakan kolom role biasa
        if ($this->role) {
            return $this->formatRoleName($this->role);
        }

        return $default;
    }

    /**
     * Format role name to be more user-friendly
     *
     * @param string $roleName
     * @return string
     */
    protected function formatRoleName($roleName)
    {
        // Handle jika role berupa JSON string
        if (is_string($roleName) && $this->isJson($roleName)) {
            $roleData = json_decode($roleName, true);
            if (isset($roleData['name'])) {
                $roleName = $roleData['name'];
            }
        }

        // Map role names to more user-friendly names
        $roleMap = [
            'admin' => 'Administrator',
            'superadmin' => 'Super Administrator',
            'teacher' => 'Guru',
            'student' => 'Siswa',
            'staff' => 'Staf',
            'lab_assistant' => 'Asisten Lab',
            'guest' => 'Tamu'
        ];

        // Convert to lowercase for case-insensitive comparison
        $lowerRoleName = strtolower($roleName);

        // Return mapped role name if exists, otherwise use capitalized original
        return $roleMap[$lowerRoleName] ?? ucfirst($roleName);
    }

    /**
     * Check if a string is a valid JSON
     *
     * @param string $string
     * @return bool
     */
    private function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }

        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}
