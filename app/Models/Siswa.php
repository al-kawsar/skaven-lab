<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Siswa extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang digunakan oleh model ini
     *
     * @var string
     */
    protected $table = 'students';

    /**
     * Primary key untuk tabel
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Atribut yang tidak boleh di-assign secara massal
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * Atribut yang dapat diisi secara massal.
     * Disimpulkan dari migrasi dan $guarded di atas
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nis',
        'nisn',
        'alamat',
        'jenis_kelamin',
        'agama',
        'foto_siswa',
        'tgl_lahir',
    ];

    /**
     * Atribut yang harus diubah menjadi tipe data tertentu.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tgl_lahir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Atribut yang dikonversi ke objek Carbon
     *
     * @var array<int, string>
     */
    protected $dates = ['tgl_lahir', 'created_at', 'updated_at'];

    /**
     * Nilai default untuk atribut model.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'foto_siswa' => null,
    ];

    /**
     * Mengatur format tanggal lahir dari d-m-Y menjadi Y-m-d saat disimpan ke database
     *
     * @param string $value
     * @return void
     */
    public function setTglLahirAttribute($value)
    {
        $this->attributes['tgl_lahir'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    /**
     * Mengubah format tanggal lahir dari Y-m-d menjadi d-m-Y saat diambil dari database
     *
     * @param string $value
     * @return string
     */
    public function getTglLahirAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }

    /**
     * Mendapatkan nama lengkap jenis kelamin
     *
     * @return string
     */
    public function getJenisKelaminLengkapAttribute()
    {
        return $this->jenis_kelamin === 'l' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Mendapatkan usia siswa berdasarkan tanggal lahir
     *
     * @return int
     */
    public function getUsiaAttribute()
    {
        return Carbon::parse($this->attributes['tgl_lahir'])->age;
    }

    /**
     * Mendapatkan URL foto siswa dengan path lengkap
     *
     * @return string
     */
    public function getFotoUrlAttribute()
    {
        return $this->foto_siswa
            ? asset('storage/photos/' . $this->foto_siswa)
            : asset('images/default-profile.png');
    }

    /**
     * Scope query untuk mencari siswa berdasarkan nama
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%")
            ->orWhere('nis', 'like', "%{$search}%")
            ->orWhere('nisn', 'like', "%{$search}%");
    }

    /**
     * Scope query untuk memfilter siswa berdasarkan jenis kelamin
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $jenisKelamin
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJenisKelamin($query, $jenisKelamin)
    {
        return $query->where('jenis_kelamin', $jenisKelamin);
    }
}
