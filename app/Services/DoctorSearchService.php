<?php

namespace App\Services;

use App\Models\Doctor;
use Illuminate\Support\Collection;

class DoctorSearchService
{
    /**
     * Search for doctors using Algolia with symptom matching
     */
    public function searchDoctors(string $query, array $filters = []): Collection
    {
        error_log("Searching doctors with query: {$query} and filters: " . json_encode($filters));
        // If no query provided, return all available doctors
        if (empty(trim($query))) {
            return $this->getAllAvailableDoctors($filters);
        }

        try {
            // First, try Algolia search
            $algoliaResults = $this->searchWithAlgolia($query, $filters);
            
            // If Algolia returns results, use them
            if ($algoliaResults->isNotEmpty()) {
                return $algoliaResults;
            }
            
            // Fallback to database search if Algolia fails or returns no results
            return $this->fallbackDatabaseSearch($query, $filters);
            
        } catch (\Exception $e) {
            // Log the error and fallback to database search
            \Log::warning('Algolia search failed, falling back to database search', [
                'error' => $e->getMessage(),
                'query' => $query
            ]);
            error_log("Algolia search failed: " . $e->getMessage());
            return $this->fallbackDatabaseSearch($query, $filters);
        }
    }

    /**
     * Search using Algolia Scout
     */
    private function searchWithAlgolia(string $query, array $filters = []): Collection
    {
        error_log("Performing Algolia search for query: {$query} with filters: " . json_encode($filters));
        $searchQuery = Doctor::search($query);
        
        // Apply filters
        if (!empty($filters['service_id'])) {
            $searchQuery->where('services', $filters['service_id']);
        }
        
        if (!empty($filters['specialization'])) {
            $searchQuery->where('specialization', $filters['specialization']);
        }
        
        // Always filter for available doctors
        $searchQuery->where('is_available', true);
          // Get results and load relationships
        $results = $searchQuery->get();
        
        // Load necessary relationships
        $results->load(['user', 'services', 'ratings']);
        
        // If we have specific symptom matches, prioritize them
        $prioritizedResults = $this->prioritizeSymptomMatches($results, $query);
        
        // Add general consultants as fallback if we have few results
        if ($prioritizedResults->count() < 3) {
            $generalConsultants = $this->getGeneralConsultants($filters);
            $prioritizedResults = $prioritizedResults->merge($generalConsultants)->unique('id');
        }
        
        return $prioritizedResults;
    }

    /**
     * Fallback database search when Algolia is not available
     */    private function fallbackDatabaseSearch(string $query, array $filters = []): Collection
    {
        $searchQuery = Doctor::query()
            ->with(['user', 'services', 'ratings'])
            ->where('is_available', true);

        // Search by name, specialization, or bio
        $searchQuery->where(function ($q) use ($query) {
            $q->whereHas('user', function ($userQuery) use ($query) {
                $userQuery->where('name', 'LIKE', "%{$query}%");
            })
            ->orWhere('specialization', 'LIKE', "%{$query}%")
            ->orWhere('bio', 'LIKE', "%{$query}%");
        });

        // Apply filters
        if (!empty($filters['service_id'])) {
            $searchQuery->whereHas('services', function ($q) use ($filters) {
                $q->where('services.id', $filters['service_id']);
            });
        }

        if (!empty($filters['specialization'])) {
            $searchQuery->where('specialization', $filters['specialization']);
        }

        $results = $searchQuery
            ->orderBy('experience_years', 'desc')
            ->limit(20)
            ->get();

        // Add symptom-based matching
        $symptomMatches = $this->findDoctorsBySymptoms($query, $filters);
        $combinedResults = $results->merge($symptomMatches)->unique('id');

        // Add general consultants as fallback
        if ($combinedResults->count() < 3) {
            $generalConsultants = $this->getGeneralConsultants($filters);
            $combinedResults = $combinedResults->merge($generalConsultants)->unique('id');
        }

        return $combinedResults;
    }

    /**
     * Find doctors based on symptom keywords
     */
    private function findDoctorsBySymptoms(string $query, array $filters = []): Collection
    {
        $query = strtolower($query);
        $symptomSpecializations = $this->getSymptomSpecializationMap();
        
        $matchedSpecializations = [];
        
        // Check if the query contains any symptom keywords
        foreach ($symptomSpecializations as $specialization => $symptoms) {
            foreach ($symptoms as $symptom) {
                if (str_contains($query, strtolower($symptom))) {
                    $matchedSpecializations[] = $specialization;
                    break;
                }
            }
        }
        
        if (empty($matchedSpecializations)) {
            return collect();
        }
          // Find doctors with matching specializations
        $doctorQuery = Doctor::query()
            ->with(['user', 'services', 'ratings'])
            ->where('is_available', true)
            ->whereIn('specialization', $matchedSpecializations);
            
        // Apply additional filters
        if (!empty($filters['service_id'])) {
            $doctorQuery->whereHas('services', function ($q) use ($filters) {
                $q->where('services.id', $filters['service_id']);
            });
        }
        
        return $doctorQuery
            ->orderBy('experience_years', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get general consultants as fallback
     */    private function getGeneralConsultants(array $filters = []): Collection
    {
        $query = Doctor::query()
            ->with(['user', 'services', 'ratings'])
            ->where('is_available', true)
            ->where(function ($q) {
                $q->where('specialization', 'General Practice')
                  ->orWhere('specialization', 'Family Medicine')
                  ->orWhere('specialization', 'Internal Medicine')
                  ->orWhere('specialization', 'General Medicine');
            });

        // Apply service filter if provided
        if (!empty($filters['service_id'])) {
            $query->whereHas('services', function ($q) use ($filters) {
                $q->where('services.id', $filters['service_id']);
            });
        }

        return $query
            ->orderBy('experience_years', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get all available doctors with filters
     */    private function getAllAvailableDoctors(array $filters = []): Collection
    {
        $query = Doctor::query()
            ->with(['user', 'services', 'ratings'])
            ->where('is_available', true);

        // Apply filters
        if (!empty($filters['service_id'])) {
            $query->whereHas('services', function ($q) use ($filters) {
                $q->where('services.id', $filters['service_id']);
            });
        }

        if (!empty($filters['specialization'])) {
            $query->where('specialization', $filters['specialization']);
        }

        return $query
            ->orderBy('experience_years', 'desc')
            ->limit(20)
            ->get();
    }

    /**
     * Prioritize results based on symptom matches
     */
    private function prioritizeSymptomMatches(Collection $results, string $query): Collection
    {
        $query = strtolower($query);
        $symptomSpecializations = $this->getSymptomSpecializationMap();
        
        return $results->sortBy(function ($doctor) use ($query, $symptomSpecializations) {
            $specialization = $doctor->specialization;
            
            // Check if this doctor's specialization matches any symptoms in the query
            if (isset($symptomSpecializations[$specialization])) {
                foreach ($symptomSpecializations[$specialization] as $symptom) {
                    if (str_contains($query, strtolower($symptom))) {
                        return 0; // High priority
                    }
                }
            }
            
            // General practitioners get medium priority
            if (in_array($specialization, ['General Practice', 'Family Medicine', 'Internal Medicine','General Medicine'])) {
                return 1;
            }
            
            return 2; // Lower priority
        })->values();
    }

    /**
     * Get symptom to specialization mapping
     */
  private function getSymptomSpecializationMap(): array
    {
        return [
            'Cardiology' => [
                'chest pain', 'heart palpitations', 'shortness of breath', 'high blood pressure',
                'irregular heartbeat', 'chest tightness', 'fatigue', 'dizziness', 'swollen ankles',
                'heart attack', 'cardiac arrest', 'cardiovascular disease', 'arrhythmia', 'angina'
            ],
            'Dermatology' => [
                'skin rash', 'acne', 'eczema', 'psoriasis', 'moles', 'skin cancer', 'wrinkles',
                'hair loss', 'dandruff', 'skin allergies', 'dermatitis', 'skin problems',
                'hives', 'sunburn', 'warts', 'fungal infection', 'nail problems'
            ],
            'Gastroenterology' => [
                'stomach pain', 'abdominal pain', 'nausea', 'vomiting', 'diarrhea', 'constipation',
                'acid reflux', 'heartburn', 'bloating', 'digestive issues', 'stomach problems',
                'irritable bowel syndrome', 'Crohn\'s disease', 'ulcer', 'gastritis', 'bloody stool'
            ],
            'Neurology' => [
                'headache', 'migraine', 'seizures', 'epilepsy', 'memory loss', 'confusion',
                'dizziness', 'numbness', 'tingling', 'stroke', 'neurological disorder', 'brain injury',
                'tremors', 'Parkinson\'s', 'Alzheimer\'s', 'vertigo', 'weakness'
            ],
            'Orthopedics' => [
                'joint pain', 'back pain', 'neck pain', 'knee pain', 'shoulder pain', 'arthritis',
                'fractures', 'sports injury', 'muscle pain', 'bone problems', 'orthopedic injury',
                'sprain', 'strain', 'tendonitis', 'ligament tear', 'sciatica', 'posture problems'
            ],
            'Pediatrics' => [
                'child fever', 'baby cough', 'vaccination', 'growth problems', 'childhood illness',
                'pediatric care', 'infant care', 'child development', 'kids health',
                'chickenpox', 'measles', 'mumps', 'colic', 'feeding issues', 'developmental delay'
            ],
            'Psychiatry' => [
                'depression', 'anxiety', 'stress', 'panic attacks', 'mental health', 'therapy',
                'counseling', 'mood disorders', 'psychiatric care', 'emotional problems',
                'bipolar disorder', 'schizophrenia', 'PTSD', 'insomnia', 'eating disorder'
            ],
            'Pulmonology' => [
                'cough', 'breathing problems', 'asthma', 'pneumonia', 'lung problems',
                'respiratory issues', 'chest congestion', 'wheezing', 'lung infection',
                'bronchitis', 'COPD', 'tuberculosis', 'sleep apnea', 'shortness of breath'
            ],
            'Urology' => [
                'urinary problems', 'kidney stones', 'bladder issues', 'prostate problems',
                'urinary tract infection', 'frequent urination', 'blood in urine', 'kidney disease',
                'incontinence', 'erectile dysfunction', 'testicular pain', 'bladder infection'
            ],
            'Gynecology' => [
                'menstrual problems', 'pregnancy', 'womens health', 'pap smear', 'contraception',
                'fertility issues', 'gynecological exam', 'period problems', 'reproductive health',
                'PCOS', 'endometriosis', 'menopause', 'vaginal discharge', 'pelvic pain'
            ],
            'Ophthalmology' => [
                'eye problems', 'vision problems', 'blurred vision', 'eye pain', 'cataracts',
                'glaucoma', 'eye exam', 'glasses', 'contact lenses', 'eye infection',
                'conjunctivitis', 'dry eyes', 'retinal detachment', 'diabetic retinopathy'
            ],
            'ENT' => [ // Ear, Nose, and Throat (Otolaryngology)
                'ear pain', 'sore throat', 'hearing problems', 'sinus problems', 'tonsillitis',
                'ear infection', 'throat infection', 'nose problems', 'ENT issues',
                'vertigo', 'dizziness', 'tinnitus', 'allergies', 'difficulty swallowing', 'hoarseness'
            ],
            'Endocrinology' => [
                'diabetes', 'thyroid problems', 'hormonal imbalance', 'weight gain', 'weight loss',
                'fatigue', 'excessive thirst', 'frequent urination', 'adrenal problems', 'metabolism issues'
            ],
            'Rheumatology' => [
                'joint inflammation', 'arthritis', 'autoimmune disease', 'muscle stiffness',
                'lupus', 'rheumatoid arthritis', 'gout', 'fibromyalgia', 'connective tissue disease'
            ],
            'Oncology' => [
                'cancer', 'tumor', 'unexplained weight loss', 'persistent fatigue', 'unusual lumps',
                'abnormal bleeding', 'skin changes', 'oncology consultation', 'chemotherapy', 'radiation'
            ],
            'Infectious Disease' => [
                'fever', 'chills', 'body aches', 'infection', 'flu', 'cold', 'virus', 'bacterial infection',
                'parasitic infection', 'travel-related illness', 'HIV', 'hepatitis', 'tuberculosis'
            ],
            'Nephrology' => [
                'kidney disease', 'kidney failure', 'dialysis', 'swelling', 'high blood pressure',
                'blood in urine', 'protein in urine', 'fluid retention', 'renal problems'
            ],
            'General Medicine' => [
                'general check-up', 'routine exam', 'common cold', 'flu symptoms', 'fever', 'fatigue',
                'minor injuries', 'wellness visit', 'preventative care', 'unknown symptoms',
                'prescription refill', 'immunization', 'general health concerns', 'referral', 'basic consultation'
            ]
        ];
    }   /**
     * Transform doctor data for frontend
     */
    public function transformDoctorData(Collection $doctors): array
    {
        return $doctors->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->user->name ?? $doctor->name,
                'specialization' => $doctor->specialization,
                'experience_years' => $doctor->experience_years,
                'consultation_fee' => $doctor->consultation_fee,
                'rating' => $doctor->average_rating ?? 0,
                'rating_count' => $doctor->rating_count ?? 0,
                'is_available' => $doctor->is_available,
                'bio' => $doctor->bio,
                'services' => $doctor->services->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name' => $service->name,
                        'price' => $service->price
                    ];
                }),
                'user' => [
                    'name' => $doctor->user->name ?? null,
                    'email' => $doctor->user->email ?? null
                ]
            ];
        })->toArray();
    }
}
