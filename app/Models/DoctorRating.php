<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DoctorRating extends Model
{
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_id',
        'rating',
        'review',
        'patient_ip',
        'user_agent',
        'is_verified',
        'is_published',
        'admin_notes',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean',
        'is_published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the doctor that was rated.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the patient who gave the rating.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the appointment that was rated.
     */
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Scope to get only published ratings.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to get only verified ratings.
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope to get ratings for a specific doctor.
     */
    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    /**
     * Check if a patient can rate a doctor based on spam prevention rules.
     */
    public static function canPatientRateDoctor($patientId, $doctorId)
    {
        // Check if patient has already rated this doctor today
        $todayRating = self::where('patient_id', $patientId)
            ->where('doctor_id', $doctorId)
            ->whereDate('created_at', Carbon::today())
            ->exists();

        if ($todayRating) {
            return false;
        }

        // Check if patient has more than 3 ratings in the last hour (spam prevention)
        $recentRatings = self::where('patient_id', $patientId)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        if ($recentRatings >= 3) {
            return false;
        }

        return true;
    }

    /**
     * Get average rating for a doctor.
     */
    public static function getAverageRatingForDoctor($doctorId)
    {
        return self::forDoctor($doctorId)
            ->published()
            ->verified()
            ->avg('rating') ?? 0;
    }

    /**
     * Get rating count for a doctor.
     */
    public static function getRatingCountForDoctor($doctorId)
    {
        return self::forDoctor($doctorId)
            ->published()
            ->verified()
            ->count();
    }

    /**
     * Get rating distribution for a doctor.
     */
    public static function getRatingDistributionForDoctor($doctorId)
    {
        $ratings = self::forDoctor($doctorId)
            ->published()
            ->verified()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->pluck('count', 'rating')
            ->toArray();

        // Fill in missing ratings with 0
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($ratings[$i])) {
                $ratings[$i] = 0;
            }
        }

        ksort($ratings);
        return $ratings;
    }

    /**
     * Check if this rating is potentially spam.
     */
    public function isPotentialSpam()
    {
        $spamScore = $this->calculateSpamScore();
        return $spamScore >= 70; // 70% threshold for spam detection
    }

    /**
     * Calculate spam score for this rating.
     */
    public function calculateSpamScore()
    {
        $score = 0;

        // Check if patient has multiple ratings for the same doctor
        $samePatientRatings = self::where('patient_id', $this->patient_id)
            ->where('doctor_id', $this->doctor_id)
            ->where('id', '!=', $this->id)
            ->count();

        if ($samePatientRatings > 0) {
            $score += 40; // High penalty for duplicate ratings
        }

        // Check if patient has posted many ratings in a short time
        $recentRatings = self::where('patient_id', $this->patient_id)
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->count();

        if ($recentRatings > 5) {
            $score += 30; // High penalty for too many ratings in 24 hours
        } elseif ($recentRatings > 3) {
            $score += 20; // Medium penalty for many ratings
        }

        // Check for suspicious review patterns
        if ($this->review) {
            $reviewLength = strlen($this->review);
            
            // Very short reviews might be spam
            if ($reviewLength < 10) {
                $score += 15;
            }
            
            // Very long reviews might be copy-paste spam
            if ($reviewLength > 2000) {
                $score += 10;
            }

            // Check for repeated characters or words
            if (preg_match('/(.)\1{4,}/', $this->review)) {
                $score += 25; // Repeated characters like "aaaaaaa"
            }
        }

        // Check if rating is extreme (1 or 5 stars) without review
        if (($this->rating == 1 || $this->rating == 5) && empty($this->review)) {
            $score += 15;
        }

        return min($score, 100); // Cap at 100%
    }

    /**
     * Mark rating as spam.
     */
    public function markAsSpam($reason = null)
    {
        $this->update([
            'is_published' => false,
            'is_spam' => true,
            'spam_reason' => $reason,
            'review_status' => 'rejected',
            'spam_score' => $this->calculateSpamScore(),
        ]);
    }

    /**
     * Mark rating as verified.
     */
    public function markAsVerified()
    {
        $this->update([
            'is_verified' => true,
            'is_published' => true,
            'is_spam' => false,
            'review_status' => 'approved',
            'verified_at' => now(),
        ]);
    }
}
