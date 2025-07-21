<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        // Create test users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@mediapp.com',
            'password' => bcrypt('password'),
            'user_type' => 'admin',
            'is_active' => true,
            'phone_number' => '+1234567890',
            'gender' => 'male',
            'address' => '123 Admin Street, Admin City',
            'date_of_birth' => '1980-01-01',
        ]);
        $admin->assignRole('admin');

        // Create AppointmentConfig
        \App\Models\AppointmentConfig::create([
            'buffer_time_before' => 10,
            'buffer_time_after' => 10,
            'max_booking_days_ahead' => 30,
            'min_booking_hours_ahead' => 2,
            'tax_rate' => 0.05,
            'tax_enabled' => true,
            'cancellation_hours_limit' => 24,
            'reschedule_hours_limit' => 24,
            'allow_cancellation' => true,
            'allow_rescheduling' => true,
            'max_appointments_per_patient_per_day' => 2,
            'max_appointments_per_doctor_per_day' => 20,
            'accepted_payment_methods' => ['cash', 'card'],
            'require_payment_on_booking' => false,
            'booking_deposit_percentage' => 0,
            'auto_approve_appointments' => true,
            'require_admin_approval' => false,
            'send_confirmation_email' => true,
            'send_reminder_email' => true,
            'reminder_hours_before' => 2,
            'default_start_time' => '09:00',
            'default_end_time' => '17:00',
            'default_slot_duration' => 30,
            'allow_emergency_bookings' => false,
            'emergency_booking_hours_limit' => 0,
            'is_active' => true,
            'timezone' => 'Asia/Kolkata',
        ]);

        // Create multiple patients
        $patients = [
            [
                'name' => 'John Smith',
                'email' => 'patient1@mediapp.com',
                'phone_number' => '+1234567891',
                'gender' => 'male',
                'address' => '456 Patient Ave, Health City',
                'date_of_birth' => '1990-05-15',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'patient2@mediapp.com',
                'phone_number' => '+1234567892',
                'gender' => 'female',
                'address' => '789 Wellness St, Care Town',
                'date_of_birth' => '1985-08-22',
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'patient3@mediapp.com',
                'phone_number' => '+1234567893',
                'gender' => 'male',
                'address' => '321 Recovery Rd, Heal Village',
                'date_of_birth' => '1992-12-10',
            ],
            [
                'name' => 'Emily Davis',
                'email' => 'patient4@mediapp.com',
                'phone_number' => '+1234567894',
                'gender' => 'female',
                'address' => '654 Treatment Blvd, Cure City',
                'date_of_birth' => '1988-03-08',
            ],
        ];

        $patientUsers = [];
        foreach ($patients as $patientData) {
            $patient = User::create([
                'name' => $patientData['name'],
                'email' => $patientData['email'],
                'password' => bcrypt('password'),
                'user_type' => 'patient',
                'is_active' => true,
                'phone_number' => $patientData['phone_number'],
                'gender' => $patientData['gender'],
                'address' => $patientData['address'],
                'date_of_birth' => $patientData['date_of_birth'],
            ]);
            $patient->assignRole('patient');
            $patientUsers[] = $patient;
        }

        // Create health profiles for patients
        $healthProfiles = [
            [
                'blood_type' => 'A+',
                'height' => 175.5,
                'weight' => 70.2,
                'allergies' => 'Peanuts, Shellfish',
                'medications' => 'Lisinopril 10mg daily',
                'medical_conditions' => 'Hypertension',
                'emergency_contact_name' => 'Jane Smith',
                'emergency_contact_phone' => '+1234567895',
                'emergency_contact_relationship' => 'Spouse',
                'insurance_provider' => 'HealthCare Plus',
                'insurance_policy_number' => 'HC123456789',
                'family_history' => 'Diabetes (Father), Heart Disease (Mother)',
                'lifestyle_notes' => 'Regular exercise, Non-smoker',
                'dietary_restrictions' => 'Vegetarian',
                'is_smoker' => false,
                'is_alcohol_consumer' => false,
                'exercise_frequency' => 'daily',
            ],
            [
                'blood_type' => 'O-',
                'height' => 162.0,
                'weight' => 58.5,
                'allergies' => 'Penicillin',
                'medications' => 'Birth control pill',
                'medical_conditions' => 'None',
                'emergency_contact_name' => 'Robert Johnson',
                'emergency_contact_phone' => '+1234567896',
                'emergency_contact_relationship' => 'Father',
                'insurance_provider' => 'MediCare Pro',
                'insurance_policy_number' => 'MC987654321',
                'family_history' => 'Breast Cancer (Grandmother)',
                'lifestyle_notes' => 'Active lifestyle, Yoga practitioner',
                'dietary_restrictions' => 'Gluten-free',
                'is_smoker' => false,
                'is_alcohol_consumer' => true,
                'exercise_frequency' => 'weekly',
            ],
            [
                'blood_type' => 'B+',
                'height' => 180.0,
                'weight' => 85.0,
                'allergies' => 'None known',
                'medications' => 'Multivitamin',
                'medical_conditions' => 'Asthma',
                'emergency_contact_name' => 'Lisa Brown',
                'emergency_contact_phone' => '+1234567897',
                'emergency_contact_relationship' => 'Sister',
                'insurance_provider' => 'WellCare Insurance',
                'insurance_policy_number' => 'WC555666777',
                'family_history' => 'Asthma (Mother)',
                'lifestyle_notes' => 'Occasional smoker, Gym member',
                'dietary_restrictions' => 'None',
                'is_smoker' => true,
                'is_alcohol_consumer' => true,
                'exercise_frequency' => 'weekly',
            ],
            [
                'blood_type' => 'AB+',
                'height' => 168.0,
                'weight' => 65.0,
                'allergies' => 'Latex, Cats',
                'medications' => 'Antihistamine as needed',
                'medical_conditions' => 'Allergic rhinitis',
                'emergency_contact_name' => 'David Davis',
                'emergency_contact_phone' => '+1234567898',
                'emergency_contact_relationship' => 'Brother',
                'insurance_provider' => 'Premium Health',
                'insurance_policy_number' => 'PH111222333',
                'family_history' => 'Allergies (Multiple family members)',
                'lifestyle_notes' => 'Avoids outdoor activities during pollen season',
                'dietary_restrictions' => 'Low sodium',
                'is_smoker' => false,
                'is_alcohol_consumer' => false,
                'exercise_frequency' => 'occasionally',
            ],
        ];

        foreach ($patientUsers as $index => $patient) {
            \App\Models\HealthProfile::create(array_merge(
                ['user_id' => $patient->id],
                $healthProfiles[$index]
            ));
        }

        // Create multiple doctors
        $doctors = [
            [
                'name' => 'Dr. John Doe',
                'email' => 'doctor1@mediapp.com',
                'specialization' => 'General Medicine',
                'qualifications' => 'MBBS, MD',
                'experience_years' => 10,
                'consultation_fee' => 500,
                'bio' => 'Experienced general physician with expertise in preventive care and chronic disease management.',
                'license_number' => 'DOC123456',
            ],
            [
                'name' => 'Dr. Sarah Wilson',
                'email' => 'doctor2@mediapp.com',
                'specialization' => 'Cardiology',
                'qualifications' => 'MBBS, MD, DM Cardiology',
                'experience_years' => 15,
                'consultation_fee' => 800,
                'bio' => 'Cardiologist specializing in interventional cardiology and heart disease prevention.',
                'license_number' => 'DOC234567',
            ],
            [
                'name' => 'Dr. Michael Chen',
                'email' => 'doctor3@mediapp.com',
                'specialization' => 'Pediatrics',
                'qualifications' => 'MBBS, MD Pediatrics',
                'experience_years' => 8,
                'consultation_fee' => 600,
                'bio' => 'Pediatrician with special interest in child development and immunizations.',
                'license_number' => 'DOC345678',
            ],
            [
                'name' => 'Dr. Lisa Anderson',
                'email' => 'doctor4@mediapp.com',
                'specialization' => 'Dermatology',
                'qualifications' => 'MBBS, MD Dermatology',
                'experience_years' => 12,
                'consultation_fee' => 700,
                'bio' => 'Dermatologist specializing in cosmetic and medical dermatology.',
                'license_number' => 'DOC456789',
            ],
        ];

        $doctorUsers = [];
        $doctorProfiles = [];
        foreach ($doctors as $doctorData) {
            $doctorUser = User::create([
                'name' => $doctorData['name'],
                'email' => $doctorData['email'],
                'password' => bcrypt('password'),
                'user_type' => 'doctor',
                'is_active' => true,
                'phone_number' => '+123456789' . (count($doctorUsers) + 1),
                'gender' => in_array($doctorData['name'], ['Dr. Sarah Wilson', 'Dr. Lisa Anderson']) ? 'female' : 'male',
                'address' => '123 Doctor St, Medical City',
                'date_of_birth' => '1975-01-0' . (count($doctorUsers) + 1),
            ]);
            $doctorUser->assignRole('doctor');
            $doctorUsers[] = $doctorUser;

            $doctor = \App\Models\Doctor::create([
                'user_id' => $doctorUser->id,
                'specialization' => $doctorData['specialization'],
                'qualifications' => $doctorData['qualifications'],
                'experience_years' => $doctorData['experience_years'],
                'consultation_fee' => $doctorData['consultation_fee'],
                'bio' => $doctorData['bio'],
                'license_number' => $doctorData['license_number'],
                'is_available' => true,
            ]);
            $doctorProfiles[] = $doctor;
        }

        // Create services
        $services = [
            [
                'name' => 'General Consultation',
                'description' => 'Consultation with a general physician.',
                'price' => 500,
                'duration_minutes' => 30,
                'category' => 'consultation',
            ],
            [
                'name' => 'Cardiac Consultation',
                'description' => 'Specialized consultation with a cardiologist.',
                'price' => 800,
                'duration_minutes' => 45,
                'category' => 'consultation',
            ],
            [
                'name' => 'Pediatric Consultation',
                'description' => 'Child health consultation with a pediatrician.',
                'price' => 600,
                'duration_minutes' => 30,
                'category' => 'consultation',
            ],
            [
                'name' => 'Dermatology Consultation',
                'description' => 'Skin and cosmetic consultation.',
                'price' => 700,
                'duration_minutes' => 30,
                'category' => 'consultation',
            ],
            [
                'name' => 'Follow-up Visit',
                'description' => 'Follow-up consultation for existing patients.',
                'price' => 300,
                'duration_minutes' => 20,
                'category' => 'follow_up',
            ],
            [
                'name' => 'Health Check-up',
                'description' => 'Comprehensive health screening.',
                'price' => 1000,
                'duration_minutes' => 60,
                'category' => 'check_up',
            ],
        ];

        $serviceModels = [];
        foreach ($services as $serviceData) {
            $service = \App\Models\Service::create(array_merge($serviceData, [
                'is_active' => true,
            ]));
            $serviceModels[] = $service;
        }

        // Attach services to doctors
        $doctorProfiles[0]->services()->attach([$serviceModels[0]->id, $serviceModels[4]->id, $serviceModels[5]->id]); // General Medicine
        $doctorProfiles[1]->services()->attach([$serviceModels[1]->id, $serviceModels[4]->id]); // Cardiology
        $doctorProfiles[2]->services()->attach([$serviceModels[2]->id, $serviceModels[4]->id]); // Pediatrics
        $doctorProfiles[3]->services()->attach([$serviceModels[3]->id, $serviceModels[4]->id]); // Dermatology

        // Create doctor schedules for all doctors
        $daysOfWeek = [1, 2, 3, 4, 5]; // Monday to Friday
        foreach ($doctorProfiles as $doctor) {
            foreach ($daysOfWeek as $day) {
                \App\Models\DoctorSchedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_available' => true,
                ]);
            }
        }

        // Create laboratory users and profiles
        $laboratories = [
            [
                'name' => 'Central Diagnostic Lab',
                'description' => 'Full-service diagnostic laboratory with advanced equipment.',
                'address' => '123 Lab Street',
                'city' => 'Lab City',
                'state' => 'Lab State',
                'postal_code' => '12345',
                'license_number' => 'LAB123456',
            ],
            [
                'name' => 'QuickTest Laboratory',
                'description' => 'Express diagnostic services with same-day results.',
                'address' => '456 Test Avenue',
                'city' => 'Test Town',
                'state' => 'Test State',
                'postal_code' => '23456',
                'license_number' => 'LAB234567',
            ],
        ];

        $labUsers = [];
        $labProfiles = [];
        foreach ($laboratories as $index => $labData) {
            $labUser = User::create([
                'name' => $labData['name'] . ' Staff',
                'email' => 'lab' . ($index + 1) . '@mediapp.com',
                'password' => bcrypt('password'),
                'user_type' => 'laboratory_staff',
                'is_active' => true,
                'phone_number' => '+111222333' . $index,
                'address' => $labData['address'] . ', ' . $labData['city'],
            ]);
            $labUser->assignRole('laboratory_staff');
            $labUsers[] = $labUser;

            $lab = \App\Models\Laboratory::create([
                'user_id' => $labUser->id,
                'name' => $labData['name'],
                'description' => $labData['description'],
                'address' => $labData['address'],
                'city' => $labData['city'],
                'state' => $labData['state'],
                'postal_code' => $labData['postal_code'],
                'country' => 'Country',
                'license_number' => $labData['license_number'],
                'is_available' => true,
            ]);
            $labProfiles[] = $lab;
        }

        // Create pharmacy users and profiles
        $pharmacies = [
            [
                'pharmacy_name' => 'City Pharmacy',
                'address' => '456 Market St',
                'city' => 'Pharma City',
                'state' => 'Pharma State',
                'postal_code' => '67890',
                'license_number' => 'PHARM123456',
            ],
            [
                'pharmacy_name' => 'HealthCare Pharmacy',
                'address' => '789 Health Blvd',
                'city' => 'Medicine Town',
                'state' => 'Medicine State',
                'postal_code' => '78901',
                'license_number' => 'PHARM234567',
            ],
        ];

        $pharmacyUsers = [];
        $pharmacyProfiles = [];
        foreach ($pharmacies as $index => $pharmacyData) {
            $pharmacyUser = User::create([
                'name' => $pharmacyData['pharmacy_name'] . ' Staff',
                'email' => 'pharmacy' . ($index + 1) . '@mediapp.com',
                'password' => bcrypt('password'),
                'user_type' => 'pharmacist',
                'is_active' => true,
                'phone_number' => '+222333444' . $index,
                'address' => $pharmacyData['address'] . ', ' . $pharmacyData['city'],
            ]);
            $pharmacyUser->assignRole('pharmacist');
            $pharmacyUsers[] = $pharmacyUser;

            $pharmacy = \App\Models\Pharmacy::create([
                'user_id' => $pharmacyUser->id,
                'pharmacy_name' => $pharmacyData['pharmacy_name'],
                'address' => $pharmacyData['address'],
                'city' => $pharmacyData['city'],
                'state' => $pharmacyData['state'],
                'postal_code' => $pharmacyData['postal_code'],
                'country' => 'Country',
                'license_number' => $pharmacyData['license_number'],
                'is_available' => true,
            ]);
            $pharmacyProfiles[] = $pharmacy;
        }

        // Create medications
        $medications = [
            [
                'name' => 'Paracetamol',
                'generic_name' => 'Acetaminophen',
                'brand_name' => 'Tylenol',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'description' => 'Pain reliever and fever reducer',
                'manufacturer' => 'PharmaCorp',
                'price' => 5.50,
                'requires_prescription' => false,
                'is_controlled' => false,
                'side_effects' => 'Rare: allergic reactions, liver damage with overdose',
                'contraindications' => 'Severe liver disease',
                'warnings' => 'Do not exceed recommended dose',
            ],
            [
                'name' => 'Amoxicillin',
                'generic_name' => 'Amoxicillin',
                'brand_name' => 'Amoxil',
                'dosage_form' => 'Capsule',
                'strength' => '250mg',
                'description' => 'Antibiotic for bacterial infections',
                'manufacturer' => 'MediPharm',
                'price' => 12.75,
                'requires_prescription' => true,
                'is_controlled' => false,
                'side_effects' => 'Nausea, diarrhea, allergic reactions',
                'contraindications' => 'Penicillin allergy',
                'warnings' => 'Complete full course even if feeling better',
            ],
            [
                'name' => 'Lisinopril',
                'generic_name' => 'Lisinopril',
                'brand_name' => 'Prinivil',
                'dosage_form' => 'Tablet',
                'strength' => '10mg',
                'description' => 'ACE inhibitor for high blood pressure',
                'manufacturer' => 'CardioMed',
                'price' => 8.25,
                'requires_prescription' => true,
                'is_controlled' => false,
                'side_effects' => 'Dry cough, dizziness, hyperkalemia',
                'contraindications' => 'Pregnancy, angioedema history',
                'warnings' => 'Monitor kidney function and potassium levels',
            ],
            [
                'name' => 'Salbutamol',
                'generic_name' => 'Albuterol',
                'brand_name' => 'Ventolin',
                'dosage_form' => 'Inhaler',
                'strength' => '100mcg/dose',
                'description' => 'Bronchodilator for asthma and COPD',
                'manufacturer' => 'RespiCare',
                'price' => 15.00,
                'requires_prescription' => true,
                'is_controlled' => false,
                'side_effects' => 'Tremor, palpitations, nervousness',
                'contraindications' => 'Hypersensitivity to salbutamol',
                'warnings' => 'Seek medical attention if symptoms worsen',
            ],
            [
                'name' => 'Metformin',
                'generic_name' => 'Metformin',
                'brand_name' => 'Glucophage',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'description' => 'Diabetes medication',
                'manufacturer' => 'DiabetesCare',
                'price' => 6.50,
                'requires_prescription' => true,
                'is_controlled' => false,
                'side_effects' => 'Nausea, diarrhea, metallic taste',
                'contraindications' => 'Severe kidney disease, metabolic acidosis',
                'warnings' => 'Monitor kidney function regularly',
            ],
        ];

        $medicationModels = [];
        foreach ($medications as $medicationData) {
            $medication = \App\Models\Medication::create(array_merge($medicationData, [
                'is_active' => true,
            ]));
            $medicationModels[] = $medication;
        }

        // Create sample appointments
        $appointmentStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        $appointmentTypes = ['consultation', 'follow_up', 'check_up'];
        $priorities = ['low', 'medium', 'high'];

        for ($i = 0; $i < 20; $i++) {
            $patient = $patientUsers[array_rand($patientUsers)];
            $doctor = $doctorProfiles[array_rand($doctorProfiles)];
            
            // Ensure the doctor has services before creating appointment
            $service = $doctor->services()->first();
            if (!$service) {
                // Skip this iteration if doctor has no services
                continue;
            }
            
            $appointmentDate = Carbon::now()->addDays(rand(-10, 30));
            $startTime = Carbon::createFromTime(rand(9, 16), rand(0, 1) * 30);
            $endTime = $startTime->copy()->addMinutes($service->duration_minutes);
            
            \App\Models\Appointment::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'service_id' => $service->id,
                'appointment_date' => $appointmentDate->format('Y-m-d'),
                'start_time' => $startTime->format('H:i'),
                'end_time' => $endTime->format('H:i'),
                'duration_minutes' => $service->duration_minutes,
                'reason' => 'Regular checkup and consultation',
                'symptoms' => rand(0, 1) ? 'Headache, fatigue' : null,
                'notes' => rand(0, 1) ? 'Patient has been feeling unwell' : null,
                'priority' => $priorities[array_rand($priorities)],
                'appointment_type' => $appointmentTypes[array_rand($appointmentTypes)],
                'status' => $appointmentStatuses[array_rand($appointmentStatuses)],
                'payment_status' => rand(0, 1) ? 'paid' : 'pending',
                'total_amount' => $service->price,
                'paid_amount' => rand(0, 1) ? $service->price : 0,
                'tax_amount' => $service->price * 0.05,
                'tax_percentage' => 5.00,
                'booking_source' => ['online', 'phone', 'walk_in'][array_rand(['online', 'phone', 'walk_in'])],
            ]);
        }

        // Create sample medical reports
        for ($i = 0; $i < 10; $i++) {
            $patient = $patientUsers[array_rand($patientUsers)];
            $doctor = $doctorProfiles[array_rand($doctorProfiles)];
            
            \App\Models\MedicalReport::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'title' => 'Medical Report #' . ($i + 1),
                'report_type' => ['Consultation', 'Follow-up', 'Emergency'][array_rand(['Consultation', 'Follow-up', 'Emergency'])],
                'consultation_date' => Carbon::now()->subDays(rand(1, 30)),
                'chief_complaint' => 'Patient presents with fever, cough, and fatigue',
                'history_of_present_illness' => 'Symptoms started 3 days ago, progressively worsening',
                'physical_examination' => 'Physical examination shows normal findings except for elevated temperature',
                'assessment_diagnosis' => 'Viral upper respiratory tract infection',
                'treatment_plan' => 'Rest, increased fluid intake, symptomatic treatment',
                'medications_prescribed' => 'Paracetamol 500mg every 6 hours as needed for fever',
                'follow_up_instructions' => 'Follow up in 1 week if symptoms persist or worsen',
                'additional_notes' => 'Patient advised to monitor symptoms and return if condition deteriorates',
                'vital_signs' => [
                    'temperature' => '101.2°F',
                    'blood_pressure' => '120/80 mmHg',
                    'pulse' => '88 bpm',
                    'respiratory_rate' => '18/min'
                ],
                'priority_level' => ['routine', 'urgent'][array_rand(['routine', 'urgent'])],
                'follow_up_required' => rand(0, 1) ? '1 week' : null,
                'status' => 'completed',
                'completed_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }

        // Create sample prescriptions
        for ($i = 0; $i < 8; $i++) {
            $patient = $patientUsers[array_rand($patientUsers)];
            $doctor = $doctorProfiles[array_rand($doctorProfiles)];
            
            $prescription = \App\Models\Prescription::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'prescription_number' => 'RX' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'notes' => 'Take medications as prescribed',
                'status' => ['pending', 'dispensed', 'completed'][array_rand(['pending', 'dispensed', 'completed'])],
                'prescribed_date' => Carbon::now()->subDays(rand(1, 10)),
                'valid_until' => Carbon::now()->addDays(30),
                'is_repeatable' => rand(0, 1),
                'refills_allowed' => rand(0, 3),
                'refills_remaining' => rand(0, 3),
            ]);

            // Add medications to prescription
            $numMedications = rand(1, 3);
            $selectedMedications = array_rand($medicationModels, $numMedications);
            if (!is_array($selectedMedications)) {
                $selectedMedications = [$selectedMedications];
            }

            foreach ($selectedMedications as $medIndex) {
                $medication = $medicationModels[$medIndex];
                $prescription->medications()->attach($medication->id, [
                    'dosage' => $medication->strength,
                    'frequency' => ['Once daily', 'Twice daily', 'Three times daily'][array_rand(['Once daily', 'Twice daily', 'Three times daily'])],
                    'duration' => rand(7, 30) . ' days',
                    'instructions' => 'Take with food',
                    'quantity_prescribed' => rand(10, 30),
                    'quantity_dispensed' => rand(10, 30),
                    'unit_price' => $medication->price,
                    'total_price' => $medication->price * rand(10, 30),
                ]);
            }
        }

        // Create sample lab test requests
        $testTypes = ['blood', 'urine', 'x-ray', 'mri', 'ct_scan', 'ultrasound'];
        $testNames = [
            'Complete Blood Count',
            'Liver Function Test',
            'Kidney Function Test',
            'Lipid Profile',
            'Thyroid Function Test',
            'Chest X-Ray',
            'Abdominal Ultrasound',
            'MRI Brain',
        ];

        for ($i = 0; $i < 12; $i++) {
            $patient = $patientUsers[array_rand($patientUsers)];
            $doctor = $doctorProfiles[array_rand($doctorProfiles)];
            $lab = $labProfiles[array_rand($labProfiles)];
            
            \App\Models\LabTestRequest::create([
                'doctor_id' => $doctor->id,
                'patient_id' => $patient->id,
                'laboratory_id' => $lab->id,
                'request_number' => 'LAB' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'test_name' => $testNames[array_rand($testNames)],
                'test_type' => $testTypes[array_rand($testTypes)],
                'test_description' => 'Routine diagnostic test',
                'clinical_notes' => 'Patient presenting with relevant symptoms',
                'priority' => ['routine', 'urgent', 'stat'][array_rand(['routine', 'urgent', 'stat'])],
                'status' => ['pending', 'scheduled', 'completed', 'cancelled'][array_rand(['pending', 'scheduled', 'completed', 'cancelled'])],
                'requested_date' => Carbon::now()->subDays(rand(1, 15)),
                'preferred_date' => Carbon::now()->addDays(rand(1, 7)),
                'estimated_cost' => rand(50, 500),
                'actual_cost' => rand(50, 500),
            ]);
        }

        // Create sample pharmacy orders
        for ($i = 0; $i < 6; $i++) {
            $patient = $patientUsers[array_rand($patientUsers)];
            $pharmacy = $pharmacyProfiles[array_rand($pharmacyProfiles)];
            
            // Get a prescription for this patient
            $prescription = \App\Models\Prescription::where('patient_id', $patient->id)->first();
            
            if ($prescription) {
                $subtotal = rand(50, 200);
                $deliveryFee = rand(5, 15);
                $taxAmount = $subtotal * 0.05;
                $totalAmount = $subtotal + $deliveryFee + $taxAmount;
                
                $order = \App\Models\PharmacyOrder::create([
                    'order_number' => 'PO' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                    'prescription_id' => $prescription->id,
                    'patient_id' => $patient->id,
                    'pharmacy_id' => $pharmacy->id,
                    'status' => ['pending', 'confirmed', 'ready', 'delivered'][array_rand(['pending', 'confirmed', 'ready', 'delivered'])],
                    'payment_status' => ['pending', 'paid', 'refunded'][array_rand(['pending', 'paid', 'refunded'])],
                    'delivery_method' => ['pickup', 'delivery'][array_rand(['pickup', 'delivery'])],
                    'delivery_address' => $patient->address,
                    'subtotal' => $subtotal,
                    'delivery_fee' => $deliveryFee,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'notes' => 'Please call before delivery',
                ]);

                // Add order items
                $prescriptionMedications = $prescription->medications;
                foreach ($prescriptionMedications as $medication) {
                    \App\Models\PharmacyOrderItem::create([
                        'pharmacy_order_id' => $order->id,
                        'prescription_medication_id' => $medication->pivot->id ?? 1, // Use pivot ID or fallback
                        'medication_name' => $medication->name,
                        'dosage' => $medication->pivot->dosage,
                        'frequency' => $medication->pivot->frequency,
                        'duration' => $medication->pivot->duration,
                        'instructions' => $medication->pivot->instructions,
                        'quantity_prescribed' => $medication->pivot->quantity_prescribed,
                        'quantity_dispensed' => $medication->pivot->quantity_dispensed,
                        'is_available' => true,
                        'unit_price' => $medication->price,
                        'total_price' => $medication->price * $medication->pivot->quantity_prescribed,
                        'pharmacy_notes' => 'As prescribed',
                        'status' => 'available',
                    ]);
                }
            }
        }

        // Create health profile permissions (ensure unique patient-doctor combinations)
        $createdPermissions = [];
        for ($i = 0; $i < 8; $i++) {
            $attempts = 0;
            do {
                $patient = $patientUsers[array_rand($patientUsers)];
                $doctor = $doctorUsers[array_rand($doctorUsers)];
                $combination = $patient->id . '-' . $doctor->id;
                $attempts++;
            } while (in_array($combination, $createdPermissions) && $attempts < 10);
            
            // Skip if we couldn't find a unique combination after 10 attempts
            if (in_array($combination, $createdPermissions)) {
                continue;
            }
            
            $createdPermissions[] = $combination;
            $isGranted = rand(0, 1);
            \App\Models\HealthProfilePermission::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'is_granted' => $isGranted,
                'granted_at' => $isGranted ? Carbon::now()->subDays(rand(1, 30)) : null,
                'revoked_at' => null,
                'notes' => 'Permission for accessing health profile',
            ]);
        }

        // Create doctor ratings (only for completed appointments)
        $completedAppointments = \App\Models\Appointment::where('status', 'completed')->get();
        
        foreach ($completedAppointments->take(10) as $appointment) {
            // Check if rating already exists for this appointment
            $existingRating = \App\Models\DoctorRating::where('appointment_id', $appointment->id)->first();
            
            if (!$existingRating) {
                \App\Models\DoctorRating::create([
                    'doctor_id' => $appointment->doctor_id,
                    'patient_id' => $appointment->patient_id,
                    'appointment_id' => $appointment->id,
                    'rating' => rand(3, 5),
                    'review' => 'Great doctor, very professional and caring.',
                    'is_verified' => true,
                    'is_published' => true,
                    'patient_ip' => '127.0.0.1',
                    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ]);
            }
        }

        // Create holidays
        \App\Models\Holiday::create([
            'name' => 'New Year Day',
            'date' => Carbon::create(2025, 1, 1),
            'recurring_yearly' => true,
            'description' => 'Public holiday - New Year',
            'type' => 'national',
            'is_active' => true,
        ]);

        \App\Models\Holiday::create([
            'name' => 'Christmas Day',
            'date' => Carbon::create(2025, 12, 25),
            'recurring_yearly' => true,
            'description' => 'Public holiday - Christmas',
            'type' => 'national',
            'is_active' => true,
        ]);

        // Create doctor holidays
        foreach ($doctorProfiles as $doctor) {
            \App\Models\DoctorHoliday::create([
                'doctor_id' => $doctor->id,
                'title' => 'Annual Vacation',
                'start_date' => Carbon::now()->addDays(60),
                'end_date' => Carbon::now()->addDays(67),
                'type' => 'vacation',
                'reason' => 'Annual vacation',
                'status' => 'approved',
                'is_active' => true,
            ]);
        }

        $this->command->info('Database seeded successfully with comprehensive demo data!');
        $this->command->info('Login credentials:');
        $this->command->info('Admin: admin@mediapp.com / password');
        $this->command->info('Doctors: doctor1@mediapp.com to doctor4@mediapp.com / password');
        $this->command->info('Patients: patient1@mediapp.com to patient4@mediapp.com / password');
        $this->command->info('Labs: lab1@mediapp.com, lab2@mediapp.com / password');
        $this->command->info('Pharmacies: pharmacy1@mediapp.com, pharmacy2@mediapp.com / password');
    }
}
