<?php

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Seeder;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medications = [
            [
                'name' => 'Paracetamol',
                'generic_name' => 'Acetaminophen',
                'brand_name' => 'Tylenol',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'description' => 'Pain reliever and fever reducer',
                'manufacturer' => 'Generic Pharma',
                'price' => 5.99,
                'requires_prescription' => false,
                'is_controlled' => false,
                'side_effects' => 'Rare: Nausea, allergic reactions',
                'contraindications' => 'Severe liver disease',
                'warnings' => 'Do not exceed recommended dose',
                'is_active' => true,
            ],
            [
                'name' => 'Amoxicillin',
                'generic_name' => 'Amoxicillin',
                'brand_name' => 'Amoxil',
                'dosage_form' => 'Capsule',
                'strength' => '250mg',
                'description' => 'Antibiotic for bacterial infections',
                'manufacturer' => 'Pharma Corp',
                'price' => 15.50,
                'requires_prescription' => true,
                'is_controlled' => false,
                'side_effects' => 'Nausea, diarrhea, allergic reactions',
                'contraindications' => 'Penicillin allergy',
                'warnings' => 'Complete full course as prescribed',
                'is_active' => true,
            ],
            [
                'name' => 'Ibuprofen',
                'generic_name' => 'Ibuprofen',
                'brand_name' => 'Advil',
                'dosage_form' => 'Tablet',
                'strength' => '200mg',
                'description' => 'Anti-inflammatory pain reliever',
                'manufacturer' => 'Generic Pharma',
                'price' => 8.99,
                'requires_prescription' => false,
                'is_controlled' => false,
                'side_effects' => 'Stomach upset, headache',
                'contraindications' => 'Peptic ulcer, kidney disease',
                'warnings' => 'Take with food to reduce stomach upset',
                'is_active' => true,
            ],
            [
                'name' => 'Metformin',
                'generic_name' => 'Metformin HCl',
                'brand_name' => 'Glucophage',
                'dosage_form' => 'Tablet',
                'strength' => '500mg',
                'description' => 'Diabetes medication',
                'manufacturer' => 'Diabetes Care Inc',
                'price' => 25.00,
                'requires_prescription' => true,
                'is_controlled' => false,
                'side_effects' => 'Nausea, diarrhea, metallic taste',
                'contraindications' => 'Kidney disease, liver disease',
                'warnings' => 'Monitor blood sugar levels regularly',
                'is_active' => true,
            ],
            [
                'name' => 'Lisinopril',
                'generic_name' => 'Lisinopril',
                'brand_name' => 'Prinivil',
                'dosage_form' => 'Tablet',
                'strength' => '10mg',
                'description' => 'ACE inhibitor for blood pressure',
                'manufacturer' => 'Heart Health Pharma',
                'price' => 12.75,
                'requires_prescription' => true,
                'is_controlled' => false,
                'side_effects' => 'Dry cough, dizziness, hyperkalemia',
                'contraindications' => 'Angioedema history, pregnancy',
                'warnings' => 'Monitor kidney function and potassium levels',
                'is_active' => true,
            ],
            [
                'name' => 'Omeprazole',
                'generic_name' => 'Omeprazole',
                'brand_name' => 'Prilosec',
                'dosage_form' => 'Capsule',
                'strength' => '20mg',
                'description' => 'Proton pump inhibitor for acid reflux',
                'manufacturer' => 'Gastro Meds',
                'price' => 18.50,
                'requires_prescription' => true,
                'is_controlled' => false,
                'side_effects' => 'Headache, nausea, abdominal pain',
                'contraindications' => 'None major',
                'warnings' => 'Long-term use may affect bone density',
                'is_active' => true,
            ],
            [
                'name' => 'Azithromycin',
                'generic_name' => 'Azithromycin',
                'brand_name' => 'Zithromax',
                'dosage_form' => 'Tablet',
                'strength' => '250mg',
                'description' => 'Macrolide antibiotic',
                'manufacturer' => 'Antibiotic Solutions',
                'price' => 22.00,
                'requires_prescription' => true,
                'is_controlled' => false,
                'side_effects' => 'Nausea, diarrhea, abdominal pain',
                'contraindications' => 'Macrolide allergy, liver disease',
                'warnings' => 'May cause QT prolongation',
                'is_active' => true,
            ],
            [
                'name' => 'Cetirizine',
                'generic_name' => 'Cetirizine HCl',
                'brand_name' => 'Zyrtec',
                'dosage_form' => 'Tablet',
                'strength' => '10mg',
                'description' => 'Antihistamine for allergies',
                'manufacturer' => 'Allergy Relief Co',
                'price' => 9.99,
                'requires_prescription' => false,
                'is_controlled' => false,
                'side_effects' => 'Drowsiness, dry mouth',
                'contraindications' => 'Severe kidney disease',
                'warnings' => 'May cause drowsiness',
                'is_active' => true,
            ],
        ];

        foreach ($medications as $medication) {
            Medication::create($medication);
        }
    }
}
