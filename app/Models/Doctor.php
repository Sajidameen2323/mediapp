<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class Doctor extends Model
{
    use Searchable;
    protected $fillable = [
        'user_id',
        'specialization',
        'qualifications',
        'experience_years',
        'license_number',
        'consultation_fee',
        'bio',
        'profile_image',
        'is_available',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'consultation_fee' => 'decimal:2',
    ];

    /**
     * Get the user associated with the doctor.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the schedules for the doctor.
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    /**
     * Get the services associated with the doctor.
     */
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)
                    ->select('services.id', 'services.name', 'services.description', 'services.price', 'services.duration_minutes', 'services.category', 'services.is_active');
    }

    /**
     * Get the breaks for the doctor.
     */
    public function breaks(): HasMany
    {
        return $this->hasMany(DoctorBreak::class);
    }

    /**
     * Get the holidays for the doctor.
     */
    public function holidays(): HasMany
    {
        return $this->hasMany(DoctorHoliday::class);
    }

    /**
     * Get the medical reports created by the doctor.
     */
    public function medicalReports(): HasMany
    {
        return $this->hasMany(MedicalReport::class);
    }

    /**
     * Get the medical report access records for the doctor.
     */
    public function medicalReportAccess(): HasMany
    {
        return $this->hasMany(MedicalReportAccess::class);
    }

    /**
     * Get medical reports that this doctor has access to (including authored ones).
     */
    public function accessibleMedicalReports()
    {
        return $this->belongsToMany(MedicalReport::class, 'medical_report_access')
            ->wherePivot('status', 'active')
            ->where(function ($query) {
                $query->wherePivot('expires_at', '>', now())
                    ->orWherePivotNull('expires_at');
            })
            ->withPivot(['access_type', 'status', 'granted_at', 'expires_at', 'notes']);
    }

    /**
     * Get the appointments for the doctor.
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the ratings for the doctor.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(DoctorRating::class);
    }

    /**
     * Get published and verified ratings for the doctor.
     */
    public function publishedRatings(): HasMany
    {
        return $this->hasMany(DoctorRating::class)
                    ->where('is_published', true)
                    ->where('is_verified', true);
    }

    /**
     * Get average rating for the doctor.
     */
    public function getAverageRatingAttribute()
    {
        return DoctorRating::getAverageRatingForDoctor($this->id);
    }

    /**
     * Get rating count for the doctor.
     */
    public function getRatingCountAttribute()
    {
        return DoctorRating::getRatingCountForDoctor($this->id);
    }

    /**
     * Get rating distribution for the doctor.
     */
    public function getRatingDistribution()
    {
        return DoctorRating::getRatingDistributionForDoctor($this->id);
    }

    /**
     * Get health profile permissions this doctor has received from patients
     */
    public function healthProfilePermissionsReceived(): HasMany
    {
        return $this->hasMany(HealthProfilePermission::class, 'doctor_id', 'user_id');
    }

    /**
     * Scope to get available doctors.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope to filter by specialization.
     */
    public function scopeBySpecialization($query, $specialization)
    {
        return $query->where('specialization', $specialization);
    }

    /**
     * Get the indexable data array for the model.
     */
    public function toSearchableArray()
    {
        $array = $this->toArray();
        
        // Add user information for search
        $array['user_name'] = $this->user->name ?? '';
        $array['user_email'] = $this->user->email ?? '';
        
        // Add services for search
        $array['services'] = $this->services->pluck('name')->toArray();
        $array['service_names'] = $this->services->pluck('name')->implode(' ');
        
        // Add symptom keywords for specializations
        $array['symptom_keywords'] = $this->getSymptomKeywords();
        
        // Add searchable content
        $array['searchable_content'] = implode(' ', [
            $this->user->name ?? '',
            $this->specialization ?? '',
            $this->bio ?? '',
            $this->service_names ?? '',
            implode(' ', $this->getSymptomKeywords())
        ]);

        return $array;
    }

    /**
     * Get symptom keywords based on specialization
     */
    public function getSymptomKeywords()
    {
        $symptomMap = [
            'Cardiology' => [
                'chest pain', 'heart palpitations', 'shortness of breath', 'high blood pressure',
                'irregular heartbeat', 'chest tightness', 'fatigue', 'dizziness', 'swollen ankles',
                'heart attack', 'cardiac', 'cardiovascular'
            ],
            'Dermatology' => [
                'skin rash', 'acne', 'eczema', 'psoriasis', 'moles', 'skin cancer', 'wrinkles',
                'hair loss', 'dandruff', 'skin allergies', 'dermatitis', 'skin problems'
            ],
            'Gastroenterology' => [
                'stomach pain', 'abdominal pain', 'nausea', 'vomiting', 'diarrhea', 'constipation',
                'acid reflux', 'heartburn', 'bloating', 'digestive issues', 'stomach problems'
            ],
            'Neurology' => [
                'headache', 'migraine', 'seizures', 'epilepsy', 'memory loss', 'confusion',
                'dizziness', 'numbness', 'tingling', 'stroke', 'neurological', 'brain'
            ],
            'Orthopedics' => [
                'joint pain', 'back pain', 'neck pain', 'knee pain', 'shoulder pain', 'arthritis',
                'fractures', 'sports injury', 'muscle pain', 'bone problems', 'orthopedic'
            ],
            'Pediatrics' => [
                'child fever', 'baby cough', 'vaccination', 'growth problems', 'childhood illness',
                'pediatric care', 'infant care', 'child development', 'kids health'
            ],
            'Psychiatry' => [
                'depression', 'anxiety', 'stress', 'panic attacks', 'mental health', 'therapy',
                'counseling', 'mood disorders', 'psychiatric care', 'emotional problems'
            ],
            'Pulmonology' => [
                'cough', 'breathing problems', 'asthma', 'pneumonia', 'lung problems',
                'respiratory issues', 'chest congestion', 'wheezing', 'lung infection'
            ],
            'Urology' => [
                'urinary problems', 'kidney stones', 'bladder issues', 'prostate problems',
                'urinary tract infection', 'frequent urination', 'blood in urine', 'kidney disease'
            ],
            'Gynecology' => [
                'menstrual problems', 'pregnancy', 'womens health', 'pap smear', 'contraception',
                'fertility', 'gynecological exam', 'period problems', 'reproductive health'
            ],
            'Ophthalmology' => [
                'eye problems', 'vision problems', 'blurred vision', 'eye pain', 'cataracts',
                'glaucoma', 'eye exam', 'glasses', 'contact lenses', 'eye infection'
            ],
            'ENT' => [
                'ear pain', 'sore throat', 'hearing problems', 'sinus problems', 'tonsillitis',
                'ear infection', 'throat infection', 'nose problems', 'ENT issues'
            ],
            'General Practice' => [
                'general checkup', 'routine examination', 'common cold', 'flu', 'fever',
                'general consultation', 'health screening', 'physical exam', 'primary care',
                'family medicine', 'preventive care'
            ]
        ];

        $specialization = $this->specialization ?? 'General Practice';
        
        // Return keywords for the doctor's specialization, or general practice keywords as fallback
        return $symptomMap[$specialization] ?? $symptomMap['General Practice'];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable()
    {
        return $this->is_available;
    }

    /**
     * Get the doctor's name from the associated user.
     */
    public function getNameAttribute()
    {
        return $this->user ? $this->user->name : null;
    }

    /**
     * Get the doctor's email from the associated user.
     */
    public function getEmailAttribute()
    {
        return $this->user ? $this->user->email : null;
    }
}
