<script>
    let autoSaveInterval;

    document.addEventListener('DOMContentLoaded', function() {
  
        setupKeyboardShortcuts();
        setupFormErrorHandling();
        // Initialize prescription and lab test management
        initializePrescriptionManagement();
        initializeLabTestManagement();
        updateFormProgress();

        // Add input listeners for all form fields to trigger progress updates
        const formInputs = document.querySelectorAll('input, textarea, select');
        formInputs.forEach(input => {
            input.addEventListener('input', updateFormProgress);
            input.addEventListener('change', updateFormProgress);

            // Add BMI calculation for height and weight fields
            if (input.name === 'vital_signs[height]' || input.name === 'vital_signs[weight]') {
                input.addEventListener('input', calculateBMI);
                input.addEventListener('change', calculateBMI);
            }

            // Add listeners for prescription and lab test fields
            if (input.name && input.name.includes('prescriptions[')) {
                input.addEventListener('input', updatePrescriptionSummary);
                input.addEventListener('change', updatePrescriptionSummary);
            }
            if (input.name && input.name.includes('lab_tests[')) {
                input.addEventListener('input', updateLabTestSummary);
                input.addEventListener('change', updateLabTestSummary);
            }
        });

        // Initial summary generation for existing data
        setTimeout(() => {
            // Parse existing medications_prescribed field and populate form
            populateExistingMedications();
            // Parse existing lab_tests_ordered field and populate form
            populateExistingLabTests();

            updatePrescriptionSummary();
            updateLabTestSummary();
        }, 100);

        // Patient search is not initialized here as patient is fixed for edit.
        // If patient selection needs to be changeable on edit, uncomment initializePatientSearch()
        // and ensure the patient search HTML section is present.
    });

    function setupFormErrorHandling() {
        const form = document.querySelector('form');
        if (!form) return;

        const formInputs = form.querySelectorAll('input, textarea, select');
        formInputs.forEach(input => {
            input.addEventListener('input', function() {
                this.classList.remove('border-red-500', 'dark:border-red-400');
                const errorSection = document.getElementById('form-errors-section');
                if (!errorSection.classList.contains('hidden')) {
                    setTimeout(() => {
                        hideFormErrors();
                    }, 2000);
                }
            });

            input.addEventListener('focus', function() {
                this.classList.remove('border-red-500', 'dark:border-red-400');
            });
        });
    }

    function insertTemplate(templateType) {
        const templates = {
            normal_vitals: {
                'vital_signs[blood_pressure]': '120/80 mmHg',
                'vital_signs[temperature]': '98.6°F (37°C)',
                'vital_signs[heart_rate]': '72 bpm',
                'vital_signs[weight]': '70 kg',
                'vital_signs[respiratory_rate]': '16 breaths/min',
                'vital_signs[oxygen_saturation]': '98%',
                'vital_signs[height]': '170 cm'
            },
            routine_checkup: {
                'chief_complaint': 'Routine health checkup and wellness visit',
                'physical_examination': 'General appearance: Alert and oriented, appears well\nVital signs: Within normal limits\nHEENT: Normocephalic, atraumatic. PERRLA. No lymphadenopathy\nCardiovascular: Regular rate and rhythm, no murmurs\nRespiratory: Clear to auscultation bilaterally, no wheezes or rales\nAbdomen: Soft, non-tender, non-distended, bowel sounds present\nExtremities: No edema, normal range of motion',
                'assessment_diagnosis': 'Routine health maintenance visit\nNo acute concerns identified\nOverall health status: Good',
                'treatment_plan': 'Continue current lifestyle. Follow up in 1 year or as needed.',
                'follow_up_instructions': 'Return in 1 year for routine checkup, or sooner if any new concerns arise.'
            },
            follow_up: {
                'chief_complaint': 'Follow-up visit regarding [Previous Condition/Concern]',
                'history_of_present_illness': 'Patient returns for scheduled follow-up. Reports [Improvement/No Change/Worsening] of symptoms. Currently [Taking/Not Taking] prescribed medications.',
                'assessment_diagnosis': 'Status post [Previous Diagnosis]. Condition is [Improved/Stable/Worsening].',
                'treatment_plan': 'Continue current treatment plan / Adjust medication as follows: [Details] / Refer to specialist if no improvement.',
                'follow_up_instructions': 'Return in [Timeframe] for further evaluation.'
            },
            emergency: {
                'report_type': 'Emergency',
                'priority_level': 'emergency',
                'chief_complaint': '[Describe emergency situation, e.g., Acute chest pain, Difficulty breathing, Trauma]',
                'history_of_present_illness': 'Onset: [Time]. Severity: [Scale 1-10]. Associated symptoms: [Symptoms]. Events leading to presentation: [Details].',
                'physical_examination': '[Focused emergency physical exam findings]',
                'assessment_diagnosis': '[Provisional diagnosis based on emergency presentation]',
                'treatment_plan': '[Immediate interventions, e.g., Oxygen administered, IV access established, Medications given]. Plan for admission / transfer / further urgent investigation.'
            },
            physical_exam: {
                'physical_examination': 'General: Well-developed, well-nourished, in no acute distress.\nSkin: Warm, dry, intact. No rashes or lesions.\nHEENT: Head normocephalic, atraumatic. Eyes: PERRLA, EOMI. Ears: TMs clear. Nose: No discharge. Throat: Clear.\nNeck: Supple, no lymphadenopathy, no thyromegaly.\nChest/Lungs: Clear to auscultation bilaterally. No wheezes, rales, or rhonchi.\nHeart: Regular rate and rhythm. S1, S2 normal. No murmurs, gallops, or rubs.\nAbdomen: Soft, non-tender, non-distended. Bowel sounds normal. No hepatosplenomegaly.\nExtremities: No cyanosis, clubbing, or edema. Full range of motion. Pulses 2+ throughout.\nNeurological: Alert and oriented x3. Cranial nerves II-XII intact. Motor strength 5/5 throughout. Sensation intact. Reflexes 2+ symmetric.'
            }
        };

        const currentTemplate = templates[templateType];
        if (!currentTemplate) {
            showNotification(`Template "${templateType}" not found.`, 'error');
            return;
        }

        for (const fieldName in currentTemplate) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.value = currentTemplate[fieldName];
                // Trigger input event for any listeners
                field.dispatchEvent(new Event('input'));
            } else {
                console.warn(`Field "${fieldName}" not found for template insertion.`);
            }
        }
        showNotification(`Template "${templateType}" applied.`, 'success');
    }

    function clearAllFields() {
        const form = document.querySelector('form');
        form.querySelectorAll('input[type="text"], input[type="date"], textarea').forEach(input => {
            if (input.name !== '_token' && input.name !== '_method' && input.name !==
                'patient_id') { // Don't clear hidden fields or patient_id
                input.value = '';
            }
        });
        form.querySelectorAll('select').forEach(select => {
            if (select.name !== 'patient_id') { // Don't clear patient_id select
                select.selectedIndex = 0;
            }
        });
        form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
        showNotification('All fields cleared.', 'info');
    }

    // Functions to populate existing data from medical report relationships
    function populateExistingMedications() {
        const prescriptionsData = @json($medicalReport->prescriptions ?? []);
        console.log('Existing Prescriptions Data:', prescriptionsData);

        if (!prescriptionsData || prescriptionsData.length === 0) return;

        // Iterate through each prescription
        prescriptionsData.forEach((prescription, prescriptionIndex) => {
            if (prescription.prescription_medications && prescription.prescription_medications.length > 0) {
                // Iterate through each medication in the prescription
                prescription.prescription_medications.forEach((prescriptionMedication, medIndex) => {
                    const medication = prescriptionMedication.medication;
                    const medicationName = medication ? (medication.name || medication.generic_name ||
                        medication.brand_name) : 'Unknown Medication';

                    addExistingMedication(
                        medicationName,
                        prescriptionMedication.dosage || '',
                        prescriptionMedication.frequency || '',
                        prescriptionMedication.duration || '',
                        prescriptionMedication.instructions || '',
                        prescriptionMedication.quantity_prescribed || '',
                        '0' // refills - you might want to get this from prescription.refills_allowed
                    );
                });
            }
        });
    }

    function populateExistingLabTests() {
        const labTestsData = @json($medicalReport->labTestRequests ?? []);
        console.log('Existing Lab Tests Data:', labTestsData);

        if (!labTestsData || labTestsData.length === 0) return;

        // Iterate through each lab test request
        labTestsData.forEach((labTest, index) => {

            addExistingLabTest(
                labTest.test_name || '',
                labTest.test_type || 'other',
                labTest.priority || 'routine',
                labTest.preferred_date.split('T')[0] || '',
                labTest.clinical_notes || ''
            );
        });
    }

    // Helper function to safely escape HTML values
    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        // Convert to string first to ensure .replace() method is available
        return String(unsafe)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function addExistingMedication(medicationName, dosage, frequency, duration, instructions = '', quantity = '',
        refills = '0') {
        prescriptionMedicationCount++;

        const container = document.getElementById('prescription-medications');
        const noMessage = document.getElementById('no-medications-message');

        // Escape all values for safe HTML insertion
        const safeMedicationName = escapeHtml(medicationName);
        const safeDosage = escapeHtml(dosage);
        const safeFrequency = escapeHtml(frequency);
        const safeDuration = escapeHtml(duration);
        const safeInstructions = escapeHtml(instructions);
        const safeQuantity = escapeHtml(quantity);
        const safeRefills = escapeHtml(refills);

        const medicationHtml = `
        <div class="prescription-medication border border-blue-200 dark:border-blue-600 rounded-lg p-4 bg-white dark:bg-gray-700" id="prescription-${prescriptionMedicationCount}">
            <div class="flex justify-between items-start mb-4">
                <h5 class="text-md font-semibold text-blue-800 dark:text-blue-300">
                    <i class="fas fa-pills mr-2"></i>Medication ${prescriptionMedicationCount}
                </h5>
                <button type="button" onclick="removePrescriptionMedication(${prescriptionMedicationCount})" 
                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medication Name *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][medication_name]" 
                        placeholder="Search medication..." list="medications-datalist" value="${safeMedicationName}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dosage *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][dosage]" 
                        placeholder="e.g., 500mg, 2 tablets" value="${safeDosage}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Frequency *</label>
                    <select name="prescriptions[${prescriptionMedicationCount}][frequency]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select frequency</option>
                        <option value="once daily" ${safeFrequency === 'once daily' ? 'selected' : ''}>Once daily</option>
                        <option value="twice daily" ${safeFrequency === 'twice daily' ? 'selected' : ''}>Twice daily</option>
                        <option value="three times daily" ${safeFrequency === 'three times daily' ? 'selected' : ''}>Three times daily</option>
                        <option value="four times daily" ${safeFrequency === 'four times daily' ? 'selected' : ''}>Four times daily</option>
                        <option value="every 4 hours" ${safeFrequency === 'every 4 hours' ? 'selected' : ''}>Every 4 hours</option>
                        <option value="every 6 hours" ${safeFrequency === 'every 6 hours' ? 'selected' : ''}>Every 6 hours</option>
                        <option value="every 8 hours" ${safeFrequency === 'every 8 hours' ? 'selected' : ''}>Every 8 hours</option>
                        <option value="every 12 hours" ${safeFrequency === 'every 12 hours' ? 'selected' : ''}>Every 12 hours</option>
                        <option value="as needed" ${safeFrequency === 'as needed' ? 'selected' : ''}>As needed</option>
                        <option value="weekly" ${safeFrequency === 'weekly' ? 'selected' : ''}>Weekly</option>
                        <option value="monthly" ${safeFrequency === 'monthly' ? 'selected' : ''}>Monthly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][duration]" 
                        placeholder="e.g., 7 days, 2 weeks, 1 month" value="${safeDuration}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Special Instructions</label>
                    <textarea name="prescriptions[${prescriptionMedicationCount}][instructions]" rows="2" 
                        placeholder="Special instructions, warnings, or notes..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">${safeInstructions}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                    <input type="number" name="prescriptions[${prescriptionMedicationCount}][quantity]" min="1" 
                        placeholder="Number of units" value="${safeQuantity}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Refills</label>
                    <input type="number" name="prescriptions[${prescriptionMedicationCount}][refills]" min="0" max="5" value="${safeRefills}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>
    `;

        container.insertAdjacentHTML('beforeend', medicationHtml);
        noMessage.classList.add('hidden');
    }

    function addExistingLabTest(testName, testType, priority = 'routine', preferredDate = '', clinicalNotes = '') {
        labTestRequestCount++;

        const container = document.getElementById('lab-test-requests');
        const noMessage = document.getElementById('no-lab-tests-message');

        const labTestHtml = `
        <div class="lab-test-request border border-green-200 dark:border-green-600 rounded-lg p-4 bg-white dark:bg-gray-700" id="lab-test-${labTestRequestCount}">
            <div class="flex justify-between items-start mb-4">
                <h5 class="text-md font-semibold text-green-800 dark:text-green-300">
                    <i class="fas fa-vials mr-2"></i>Lab Test ${labTestRequestCount}
                </h5>
                <button type="button" onclick="removeLabTestRequest(${labTestRequestCount})" 
                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Name *</label>
                    <input type="text" name="lab_tests[${labTestRequestCount}][test_name]" 
                        placeholder="e.g., Complete Blood Count" value="${testName}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Type *</label>
                    <select name="lab_tests[${labTestRequestCount}][test_type]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        <option value="">Select type</option>
                        <option value="blood" ${testType === 'blood' ? 'selected' : ''}>Blood Test</option>
                        <option value="urine" ${testType === 'urine' ? 'selected' : ''}>Urine Test</option>
                        <option value="stool" ${testType === 'stool' ? 'selected' : ''}>Stool Test</option>
                        <option value="imaging" ${testType === 'imaging' ? 'selected' : ''}>Imaging Study</option>
                        <option value="biopsy" ${testType === 'biopsy' ? 'selected' : ''}>Biopsy</option>
                        <option value="culture" ${testType === 'culture' ? 'selected' : ''}>Culture</option>
                        <option value="serology" ${testType === 'serology' ? 'selected' : ''}>Serology</option>
                        <option value="molecular" ${testType === 'molecular' ? 'selected' : ''}>Molecular Test</option>
                        <option value="other" ${testType === 'other' ? 'selected' : ''}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                    <select name="lab_tests[${labTestRequestCount}][priority]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="routine" ${priority === 'routine' ? 'selected' : ''}>Routine</option>
                        <option value="urgent" ${priority === 'urgent' ? 'selected' : ''}>Urgent</option>
                        <option value="stat" ${priority === 'stat' ? 'selected' : ''}>STAT (Immediate)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preferred Date</label>
                    <input type="date" name="lab_tests[${labTestRequestCount}][preferred_date]" 
                        min="${new Date().toISOString().split('T')[0]}" value="${preferredDate}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Clinical Notes</label>
                    <textarea name="lab_tests[${labTestRequestCount}][clinical_notes]" rows="2" 
                        placeholder="Clinical indication, specific requirements, or notes for the laboratory..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">${clinicalNotes}</textarea>
                </div>
            </div>
        </div>
    `;

        container.insertAdjacentHTML('beforeend', labTestHtml);
        noMessage.classList.add('hidden');
    }

    // Prescription Management Functions
    let prescriptionMedicationCount =
        {{ $medicalReport->prescriptions->sum(function ($prescription) {return $prescription->prescriptionMedications->count();}) }};
    let labTestRequestCount = {{ $medicalReport->labTestRequests ? $medicalReport->labTestRequests->count() : 0 }};

    function initializePrescriptionManagement() {
        // Ensure counter starts from existing items count to avoid ID conflicts
        const existingMedications = document.querySelectorAll('.prescription-medication');
        if (existingMedications.length > 0) {
            prescriptionMedicationCount = existingMedications.length;
        }
        updatePrescriptionDisplay();
        updatePrescriptionSummary();
    }

    function addPrescriptionMedication() {
        prescriptionMedicationCount++;

        // Ensure unique ID by checking for existing elements
        while (document.getElementById(`prescription-${prescriptionMedicationCount}`)) {
            prescriptionMedicationCount++;
        }

        const container = document.getElementById('prescription-medications');
        const noMessage = document.getElementById('no-medications-message');

        const medicationHtml = `
        <div class="prescription-medication border border-blue-200 dark:border-blue-600 rounded-lg p-4 bg-white dark:bg-gray-700" id="prescription-${prescriptionMedicationCount}">
            <div class="flex justify-between items-start mb-4">
                <h5 class="text-md font-semibold text-blue-800 dark:text-blue-300">
                    <i class="fas fa-pills mr-2"></i>Medication ${prescriptionMedicationCount}
                </h5>
                <button type="button" onclick="removePrescriptionMedication(${prescriptionMedicationCount})" 
                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medication Name *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][medication_name]" 
                        placeholder="Search medication..." list="medications-datalist"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dosage *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][dosage]" 
                        placeholder="e.g., 500mg, 2 tablets"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Frequency *</label>
                    <select name="prescriptions[${prescriptionMedicationCount}][frequency]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Select frequency</option>
                        <option value="once daily">Once daily</option>
                        <option value="twice daily">Twice daily</option>
                        <option value="three times daily">Three times daily</option>
                        <option value="four times daily">Four times daily</option>
                        <option value="every 4 hours">Every 4 hours</option>
                        <option value="every 6 hours">Every 6 hours</option>
                        <option value="every 8 hours">Every 8 hours</option>
                        <option value="every 12 hours">Every 12 hours</option>
                        <option value="as needed">As needed</option>
                        <option value="weekly">Weekly</option>
                        <option value="monthly">Monthly</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Duration *</label>
                    <input type="text" name="prescriptions[${prescriptionMedicationCount}][duration]" 
                        placeholder="e.g., 7 days, 2 weeks, 1 month"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Special Instructions</label>
                    <textarea name="prescriptions[${prescriptionMedicationCount}][instructions]" rows="2" 
                        placeholder="Special instructions, warnings, or notes..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                    <input type="number" name="prescriptions[${prescriptionMedicationCount}][quantity]" min="1" 
                        placeholder="Number of units"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Refills</label>
                    <input type="number" name="prescriptions[${prescriptionMedicationCount}][refills]" min="0" max="5" value="0"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
        </div>
    `;

        container.insertAdjacentHTML('beforeend', medicationHtml);
        noMessage.classList.add('hidden');
        updatePrescriptionSummary();
    }

    function removePrescriptionMedication(id) {
        const element = document.getElementById(`prescription-${id}`);
        if (element) {
            element.remove();
            updatePrescriptionDisplay();
            updatePrescriptionSummary();
        }
    }

    function updatePrescriptionDisplay() {
        const container = document.getElementById('prescription-medications');
        const noMessage = document.getElementById('no-medications-message');

        if (container.children.length === 0) {
            noMessage.classList.remove('hidden');
        } else {
            noMessage.classList.add('hidden');
        }
    }

    function updatePrescriptionSummary() {
        const medications = document.querySelectorAll('.prescription-medication');
        let summary = '';

        medications.forEach((med, index) => {
            const name = med.querySelector('[name*="[medication_name]"]')?.value || '';
            const dosage = med.querySelector('[name*="[dosage]"]')?.value || '';
            const frequency = med.querySelector('[name*="[frequency]"]')?.value || '';
            const duration = med.querySelector('[name*="[duration]"]')?.value || '';

            if (name || dosage || frequency || duration) {
                summary += `${index + 1}. ${name} - ${dosage} - ${frequency} for ${duration}\n`;
            }
        });

        const hiddenField = document.getElementById('medications_prescribed_hidden');
        if (hiddenField) {
            hiddenField.value = summary;
        }
    }

    // Lab Test Management Functions
    function initializeLabTestManagement() {
        // Ensure counter starts from existing items count to avoid ID conflicts
        const existingLabTests = document.querySelectorAll('.lab-test-request');
        if (existingLabTests.length > 0) {
            labTestRequestCount = existingLabTests.length;
        }
        updateLabTestDisplay();
        updateLabTestSummary();
    }

    function addLabTestRequest() {
        labTestRequestCount++;

        // Ensure unique ID by checking for existing elements
        while (document.getElementById(`lab-test-${labTestRequestCount}`)) {
            labTestRequestCount++;
        }

        const container = document.getElementById('lab-test-requests');
        const noMessage = document.getElementById('no-lab-tests-message');

        const labTestHtml = `
        <div class="lab-test-request border border-green-200 dark:border-green-600 rounded-lg p-4 bg-white dark:bg-gray-700" id="lab-test-${labTestRequestCount}">
            <div class="flex justify-between items-start mb-4">
                <h5 class="text-md font-semibold text-green-800 dark:text-green-300">
                    <i class="fas fa-vials mr-2"></i>Lab Test ${labTestRequestCount}
                </h5>
                <button type="button" onclick="removeLabTestRequest(${labTestRequestCount})" 
                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Name *</label>
                    <input type="text" name="lab_tests[${labTestRequestCount}][test_name]" 
                        placeholder="e.g., Complete Blood Count"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Test Type *</label>
                    <select name="lab_tests[${labTestRequestCount}][test_type]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                        <option value="">Select type</option>
                        <option value="blood">Blood Test</option>
                        <option value="urine">Urine Test</option>
                        <option value="stool">Stool Test</option>
                        <option value="imaging">Imaging Study</option>
                        <option value="biopsy">Biopsy</option>
                        <option value="culture">Culture</option>
                        <option value="serology">Serology</option>
                        <option value="molecular">Molecular Test</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
                    <select name="lab_tests[${labTestRequestCount}][priority]" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="routine">Routine</option>
                        <option value="urgent">Urgent</option>
                        <option value="stat">STAT (Immediate)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preferred Date</label>
                    <input type="date" name="lab_tests[${labTestRequestCount}][preferred_date]" 
                        min="${new Date().toISOString().split('T')[0]}"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Clinical Notes</label>
                    <textarea name="lab_tests[${labTestRequestCount}][clinical_notes]" rows="2" 
                        placeholder="Clinical indication, specific requirements, or notes for the laboratory..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 bg-white dark:bg-gray-600 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                </div>
            </div>
        </div>
    `;

        container.insertAdjacentHTML('beforeend', labTestHtml);
        noMessage.classList.add('hidden');
        updateLabTestSummary();
    }

    function removeLabTestRequest(id) {
        const element = document.getElementById(`lab-test-${id}`);
        if (element) {
            element.remove();
            updateLabTestDisplay();
            updateLabTestSummary();
        }
    }

    function updateLabTestDisplay() {
        const container = document.getElementById('lab-test-requests');
        const noMessage = document.getElementById('no-lab-tests-message');

        if (container.children.length === 0) {
            noMessage.classList.remove('hidden');
        } else {
            noMessage.classList.add('hidden');
        }
    }

    function updateLabTestSummary() {
        const labTests = document.querySelectorAll('.lab-test-request');
        let summary = '';

        labTests.forEach((test, index) => {
            const name = test.querySelector('[name*="[test_name]"]')?.value || '';
            const type = test.querySelector('[name*="[test_type]"]')?.value || '';
            const priority = test.querySelector('[name*="[priority]"]')?.value || '';

            if (name || type) {
                summary +=
                    `${index + 1}. ${name} (${type})${priority !== 'routine' ? ' - ' + priority.toUpperCase() : ''}\n`;
            }
        });

        const hiddenField = document.getElementById('lab_tests_ordered_hidden');
        if (hiddenField) {
            hiddenField.value = summary;
        }
    }

    // Update summaries when form fields change
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('prescriptions[')) {
            updatePrescriptionSummary();
        }
        if (e.target.name && e.target.name.includes('lab_tests[')) {
            updateLabTestSummary();
        }
        updateFormProgress();
    });

    document.addEventListener('change', function(e) {
        if (e.target.name && e.target.name.includes('prescriptions[')) {
            updatePrescriptionSummary();
        }
        if (e.target.name && e.target.name.includes('lab_tests[')) {
            updateLabTestSummary();
        }
        updateFormProgress();
    });



    // Section Toggle Functions
    function toggleSection(sectionId) {
        console.log(`Toggling section: ${sectionId}`);
        const content = document.getElementById(`${sectionId}-content`);
        const toggle = document.getElementById(`${sectionId}-toggle`);
        console.log(`Content: ${content}, Toggle: ${toggle}`);
        if (content && toggle) {
            console.log(content.style.maxHeight && content.style.maxHeight !== '100%'); 
            if (content.style.maxHeight && content.style.maxHeight !== '100%') {
                content.style.maxHeight = '100%';
                toggle.classList.remove('fa-chevron-down');
                toggle.classList.add('fa-chevron-up');
            } else {
                content.style.maxHeight = '0%';
                toggle.classList.remove('fa-chevron-up');
                toggle.classList.add('fa-chevron-down');
            }
        }
    }

    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', function(event) {
            if (event.ctrlKey) {
                switch (event.key) {
                    case 's': // Ctrl+S
                        event.preventDefault();
                        document.querySelector('button[name="status"][value="draft"]').click();
                        break;
                    case 'Enter': // Ctrl+Enter
                        event.preventDefault();
                        document.querySelector('button[name="status"][value="completed"]').click();
                        break;
                    case 'p': // Ctrl+P
                        event.preventDefault();
                        previewReport();
                        break;
                }
            }
        });
    }

    function previewReport() {
        // This function would ideally gather form data and show a formatted preview.
        // For now, it's a placeholder.
        showNotification('Report preview functionality is under development.', 'info');

    }



    // Form Error Display
    function showFormErrors(errors) {
        const errorSection = document.getElementById('form-errors-section');
        const errorList = document.getElementById('form-errors-list');

        errorList.innerHTML = ''; // Clear previous errors
        if (typeof errors === 'object' && errors !== null) {
            Object.values(errors).forEach(errorMessages => {
                errorMessages.forEach(message => {
                    const li = document.createElement('li');
                    li.textContent = message;
                    errorList.appendChild(li);
                });
            });
        } else if (typeof errors === 'string') {
            const li = document.createElement('li');
            li.textContent = errors;
            errorList.appendChild(li);
        } else {
            const li = document.createElement('li');
            li.textContent = 'An unknown error occurred.';
            errorList.appendChild(li);
        }
        errorSection.classList.remove('hidden');
        errorSection.scrollIntoView({
            behavior: 'smooth'
        });
    }

    function hideFormErrors() {
        document.getElementById('form-errors-section').classList.add('hidden');
    }

    // Utility for notifications (toast-like messages)
    function showNotification(message, type = 'info', duration = 3000) {
        const container = document.createElement('div');
        container.className =
            `fixed top-5 right-5 p-4 rounded-lg shadow-xl text-white z-[100] transition-all duration-300 transform translate-x-full`;

        let bgColor, iconClass;
        switch (type) {
            case 'success':
                bgColor = 'bg-green-500';
                iconClass = 'fas fa-check-circle';
                break;
            case 'error':
                bgColor = 'bg-red-500';
                iconClass = 'fas fa-times-circle';
                break;
            case 'warning':
                bgColor = 'bg-yellow-500';
                iconClass = 'fas fa-exclamation-triangle';
                break;
            default:
                bgColor = 'bg-blue-500';
                iconClass = 'fas fa-info-circle';
                break;
        }
        container.classList.add(bgColor);

        container.innerHTML = `
        <div class="flex items-center">
            <i class="${iconClass} mr-3 text-xl"></i>
            <span>${message}</span>
        </div>
    `;

        document.body.appendChild(container);

        // Animate in
        setTimeout(() => {
            container.classList.remove('translate-x-full');
            container.classList.add('translate-x-0');
        }, 100);

        // Animate out and remove
        setTimeout(() => {
            container.classList.add('translate-x-full');
            container.classList.remove('translate-x-0');
            setTimeout(() => {
                container.remove();
            }, 300);
        }, duration);
    }

    // Enhanced Form Functions
    function updateFormProgress() {
        const requiredFields = [
            'title', 'consultation_date', 'report_type',
            'vital_signs[blood_pressure]', 'vital_signs[temperature]',
            'vital_signs[heart_rate]', 'vital_signs[weight]'
        ];

        let completed = 0;
        requiredFields.forEach(field => {
            const element = document.querySelector(`[name="${field}"]`);
            if (element && element.value.trim() !== '') {
                completed++;
            }
        });

        const percentage = Math.round((completed / requiredFields.length) * 100);
        const progressPercentage = document.getElementById('progress-percentage');
        const progressBar = document.getElementById('progress-bar');

        if (progressPercentage) {
            progressPercentage.textContent = percentage + '%';
        }

        if (progressBar) {
            progressBar.style.width = percentage + '%';
        }

        // Update section status
        updateSectionStatus('patient', ['title', 'consultation_date', 'report_type']);
        updateSectionStatus('vitals', [
            'vital_signs[blood_pressure]', 'vital_signs[temperature]',
            'vital_signs[heart_rate]', 'vital_signs[weight]',
            'vital_signs[respiratory_rate]', 'vital_signs[oxygen_saturation]',
            'vital_signs[height]'
        ]);
        updateSectionStatus('assessment', ['chief_complaint', 'physical_examination']);
        updateSectionStatus('diagnosis', ['assessment_diagnosis', 'treatment_plan']);

        // Update vitals completion specifically
        updateVitalsCompletion();
    }

    function updateVitalsCompletion() {
        const vitalFields = [
            'vital_signs[blood_pressure]', 'vital_signs[temperature]',
            'vital_signs[heart_rate]', 'vital_signs[weight]',
            'vital_signs[respiratory_rate]', 'vital_signs[oxygen_saturation]',
            'vital_signs[height]'
        ];

        let completed = 0;
        vitalFields.forEach(field => {
            const element = document.querySelector(`[name="${field}"]`);
            if (element && element.value.trim() !== '') {
                completed++;
            }
        });

        const vitalsCompletion = document.getElementById('vitals-completion');
        const vitalsProgress = document.getElementById('vitals-progress');

        if (vitalsCompletion) {
            vitalsCompletion.textContent = `${completed}/${vitalFields.length} Complete`;
        }

        if (vitalsProgress) {
            const percentage = Math.round((completed / vitalFields.length) * 100);
            vitalsProgress.style.width = percentage + '%';
        }
    }

    function updateSectionStatus(section, fields) {
        let completed = 0;
        fields.forEach(field => {
            const element = document.querySelector(`[name="${field}"]`);
            if (element && element.value.trim() !== '') {
                completed++;
            }
        });

        const statusElement = document.getElementById(`${section}-status`);
        if (statusElement) {
            statusElement.className = 'field-status ';
            if (completed === fields.length) {
                statusElement.className += 'field-complete';
            } else if (completed > 0) {
                statusElement.className += 'field-partial';
            } else {
                statusElement.className += 'field-empty';
            }
        }
    }

    // Quick Actions
    function applyPreset(presetType) {
        switch (presetType) {
            case 'routine-checkup':
                document.querySelector('[name="chief_complaint"]').value =
                    'Routine health examination and wellness check';
                fillNormalVitals();
                break;
            case 'follow-up':
                document.querySelector('[name="chief_complaint"]').value =
                    'Follow-up appointment for previous consultation';
                break;
            case 'emergency':
                document.querySelector('[name="chief_complaint"]').value =
                    'Emergency consultation - urgent medical concern';
                break;
        }
        updateFormProgress();
        showNotification(`Applied ${presetType.replace('-', ' ')} preset`, 'success');
    }

    function fillNormalVitals() {
        document.querySelector('[name="vital_signs[blood_pressure]"]').value = '120/80';
        document.querySelector('[name="vital_signs[temperature]"]').value = '98.6°F';
        document.querySelector('[name="vital_signs[heart_rate]"]').value = '72';
        document.querySelector('[name="vital_signs[respiratory_rate]"]').value = '16';
        document.querySelector('[name="vital_signs[oxygen_saturation]"]').value = '98%';
        updateFormProgress();
        updateVitalsCompletion();
        calculateBMI();
        showNotification('Normal vital signs applied', 'success');
    }


    function calculateBMI() {
        const heightField = document.querySelector('[name="vital_signs[height]"]');
        const weightField = document.querySelector('[name="vital_signs[weight]"]');
        const bmiSection = document.getElementById('bmi-section');
        const bmiResult = document.getElementById('bmi-result');

        if (heightField && weightField && heightField.value && weightField.value) {
            const height = parseFloat(heightField.value);
            const weight = parseFloat(weightField.value);

            if (height > 0 && weight > 0) {
                const heightInMeters = height / 100;
                const bmi = weight / (heightInMeters * heightInMeters);

                let category = '';
                let color = '';

                if (bmi < 18.5) {
                    category = 'Underweight';
                    color = 'text-blue-600 dark:text-blue-400';
                } else if (bmi < 25) {
                    category = 'Normal';
                    color = 'text-green-600 dark:text-green-400';
                } else if (bmi < 30) {
                    category = 'Overweight';
                    color = 'text-yellow-600 dark:text-yellow-400';
                } else {
                    category = 'Obese';
                    color = 'text-red-600 dark:text-red-400';
                }

                if (bmiResult) {
                    bmiResult.innerHTML = `<span class="${color}">${bmi.toFixed(1)} - ${category}</span>`;
                }

                if (bmiSection) {
                    bmiSection.classList.remove('hidden');
                }
            }
        }
    }


    // Voice Input
    let recognition;
    let currentVoiceField;

    function toggleVoiceInput() {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            showNotification('Speech recognition not supported in this browser', 'error');
            return;
        }

        if (!recognition) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.continuous = true;
            recognition.interimResults = true;
            recognition.lang = 'en-US';

            recognition.onstart = () => {
                document.getElementById('voice-input-btn').innerHTML = '<i class="fas fa-stop mr-2"></i>Stop Voice';
                document.getElementById('voice-input-btn').classList.add('speech-indicator');
                showNotification('Voice input started. Click on any text field and speak.', 'info');
            };

            recognition.onend = () => {
                document.getElementById('voice-input-btn').innerHTML =
                    '<i class="fas fa-microphone mr-2"></i>Voice Input';
                document.getElementById('voice-input-btn').classList.remove('speech-indicator');
            };

            recognition.onresult = (event) => {
                let finalTranscript = '';
                for (let i = event.resultIndex; i < event.results.length; i++) {
                    if (event.results[i].isFinal) {
                        finalTranscript += event.results[i][0].transcript;
                    }
                }

                if (finalTranscript && document.activeElement &&
                    (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA')) {
                    document.activeElement.value += finalTranscript;
                    updateFormProgress();
                }
            };

            recognition.onerror = (event) => {
                showNotification('Voice recognition error: ' + event.error, 'error');
            };
        }

        if (recognition && recognition.state !== 'listening') {
            recognition.start();
        } else if (recognition) {
            recognition.stop();
        }
    }

    // Quick Tips Modal
    function showQuickTips() {
        document.getElementById('quick-tips-modal').classList.remove('hidden');
    }

    function hideQuickTips() {
        document.getElementById('quick-tips-modal').classList.add('hidden');
    }

    // Event Listeners
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.includes('vital_signs[')) {
            calculateBMI();
            updateVitalsCompletion();
        }
        updateFormProgress();
    });

    document.addEventListener('change', function(e) {
        updateFormProgress();
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+S to save
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            document.getElementById('medical-report-form').submit();
        }

        // Alt+V for voice input
        if (e.altKey && e.key === 'v') {
            e.preventDefault();
            currentVoiceField = document.activeElement;
            toggleVoiceInput();
        }

        // Alt+T for tips
        if (e.altKey && e.key === 't') {
            e.preventDefault();
            showQuickTips();
        }
    });

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to all form fields for real-time progress tracking
        const allInputs = document.querySelectorAll('input, select, textarea');
        allInputs.forEach(input => {
            if (input.name) {
                input.addEventListener('input', updateFormProgress);
                input.addEventListener('change', updateFormProgress);
            }
        });

        // Initial progress calculation
        updateFormProgress();
        updateVitalsCompletion();
        calculateBMI();
    });



    // Additional Quick Action Functions

    function toggleAllSections() {
        const sections = ['patient-info', 'vital-signs', 'clinical-assessment', 'diagnosis-treatment',
            'follow-up-notes'];
        sections.forEach(section => {
            const content = document.getElementById(`${section}-content`);
            if (content) {
                content.style.maxHeight = 'auto';
                const toggle = document.getElementById(`${section}-toggle`);
                if (toggle) toggle.className = 'fas fa-chevron-down';
            }
        });
    }

    function clearForm() {
        if (confirm('Are you sure you want to clear all form fields? This action cannot be undone.')) {
            clearAllFields();
        }
    }



    function stopVoiceInput() {
        const voiceModal = document.getElementById('voice-modal');
        if (voiceModal) {
            voiceModal.classList.add('hidden');
        }
    }

    // Preset functions for vital signs
    function fillPediatricVitals() {
        const fields = {
            'vital_signs[blood_pressure]': '100/60',
            'vital_signs[temperature]': '98.2',
            'vital_signs[heart_rate]': '110',
            'vital_signs[respiratory_rate]': '22',
            'vital_signs[oxygen_saturation]': '99'
        };

        Object.entries(fields).forEach(([name, value]) => {
            const field = document.querySelector(`[name="${name}"]`);
            if (field) field.value = value;
        });

        calculateBMI();
        updateFormProgress();
        showNotification('Pediatric vital signs filled', 'success');
    }

    function fillGeriatricVitals() {
        const fields = {
            'vital_signs[blood_pressure]': '140/80',
            'vital_signs[temperature]': '98.0',
            'vital_signs[heart_rate]': '68',
            'vital_signs[respiratory_rate]': '14',
            'vital_signs[oxygen_saturation]': '96'
        };

        Object.entries(fields).forEach(([name, value]) => {
            const field = document.querySelector(`[name="${name}"]`);
            if (field) field.value = value;
        });

        calculateBMI();
        updateFormProgress();
        showNotification('Geriatric vital signs filled', 'success');
    }
</script>
