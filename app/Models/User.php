<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'gender',
        'address',
        'date_of_birth',
        'user_type',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'date_of_birth' => 'date',
    ];

    protected $dates = ['deleted_at'];

    /**
     * Get the profile for the user based on user type.
     */
    public function profile(): array
    {
        switch ($this->user_type) {
            case 'doctor':
                return $this->doctor ? $this->doctor->toArray() : [];
            case 'laboratory':
                return $this->laboratory ? $this->laboratory->toArray() : [];
            case 'pharmacy':
                return $this->pharmacy ? $this->pharmacy->toArray() : [];
            case 'patient':
                return $this->healthProfile ? $this->healthProfile->toArray() : [];
            default:
                return [];
        }
    }

    /**
     * Get the doctor profile for the user.
     */
    public function doctor(): HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Get the laboratory profile for the user.
     */
    public function laboratory(): HasOne
    {
        return $this->hasOne(Laboratory::class);
    }

    /**
     * Get the pharmacy profile for the user.
     */
    public function pharmacy(): HasOne
    {
        return $this->hasOne(Pharmacy::class);
    }

    /**
     * Get the health profile for the user.
     */
    public function healthProfile(): HasOne
    {
        return $this->hasOne(HealthProfile::class);
    }

    /**
     * Get health profile permissions granted by this patient to doctors
     */
    public function healthProfilePermissionsGranted(): HasMany
    {
        return $this->hasMany(HealthProfilePermission::class, 'patient_id');
    }

    /**
     * Get health profile permissions this doctor has received from patients
     */
    public function healthProfilePermissionsReceived(): HasMany
    {
        return $this->hasMany(HealthProfilePermission::class, 'doctor_id');
    }

    /**
     * Check if this user (patient) has granted health profile access to a specific doctor
     */
    public function hasGrantedHealthProfileAccessTo($doctorId): bool
    {
        return $this->healthProfilePermissionsGranted()
            ->where('doctor_id', $doctorId)
            ->granted()
            ->exists();
    }

    /**
     * Check if this user (doctor) has health profile access from a specific patient
     */
    public function hasHealthProfileAccessFrom($patientId): bool
    {
        return $this->healthProfilePermissionsReceived()
            ->where('patient_id', $patientId)
            ->granted()
            ->exists();
    }

    /**
     * Get the prescriptions for this patient.
     */
    public function prescriptions(): HasMany
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    /**
     * Get the lab test requests for this patient.
     */
    public function labTestRequests(): HasMany
    {
        return $this->hasMany(LabTestRequest::class, 'patient_id');
    }

    /**
     * Get the medical reports for this patient.
     */
    public function medicalReports(): HasMany
    {
        return $this->hasMany(MedicalReport::class, 'patient_id');
    }

    /**
     * Get the appointments for this patient.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('user_type', $type);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
}
