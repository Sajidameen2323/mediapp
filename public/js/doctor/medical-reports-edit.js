/**
 * Medical Reports Edit Form JavaScript
 * Handles form interactions, validation, auto-save, and various features
 */

// Global variables
let autoSaveInterval;
let prescriptionMedicationCount = 0;
let labTestRequestCount = 0;
let recognition;
let currentVoiceField;

/**
 * Initialize the form when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', function() {
    setupAutoSave();
    setupKeyboardShortcuts();
    setupFormErrorHandling();
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
        populateExistingMedications();
        populateExistingLabTests();
        updatePrescriptionSummary();
        updateLabTestSummary();
    }, 100);
});

/**
 * Setup form error handling
 */
function setupFormErrorHandling() {
    const form = document.querySelector('form');
    if (!form) return;

    const formInputs = form.querySelectorAll('input, textarea, select');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('border-red-500', 'dark:border-red-400');
            const errorSection = document.getElementById('form-errors-section');
            if (errorSection && !errorSection.classList.contains('hidden')) {
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

/**
 * Insert predefined templates
 */
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
            field.dispatchEvent(new Event('input'));
        } else {
            console.warn(`Field "${fieldName}" not found for template insertion.`);
        }
    }
    showNotification(`Template "${templateType}" applied.`, 'success');
}

/**
 * Clear all form fields
 */
function clearAllFields() {
    const form = document.querySelector('form');
    form.querySelectorAll('input[type="text"], input[type="date"], textarea').forEach(input => {
        if (input.name !== '_token' && input.name !== '_method' && input.name !== 'patient_id') {
            input.value = '';
        }
    });
    form.querySelectorAll('select').forEach(select => {
        if (select.name !== 'patient_id') {
            select.selectedIndex = 0;
        }
    });
    form.querySelectorAll('input[type="checkbox"]').forEach(checkbox => checkbox.checked = false);
    showNotification('All fields cleared.', 'info');
}

/**
 * Populate existing medications from medical report data
 */
function populateExistingMedications() {
    // This function will be populated with actual data from the Blade template
    // The data will be injected via a global variable from the Blade template
    if (typeof window.medicalReportData !== 'undefined' && window.medicalReportData.prescriptions) {
        const prescriptionsData = window.medicalReportData.prescriptions;
        
        if (!prescriptionsData || prescriptionsData.length === 0) return;
      
        prescriptionsData.forEach((prescription, prescriptionIndex) => {
            if (prescription.prescription_medications && prescription.prescription_medications.length > 0) {
                prescription.prescription_medications.forEach((prescriptionMedication, medIndex) => {
                    const medication = prescriptionMedication.medication;
                    const medicationName = medication ? (medication.name || medication.generic_name || medication.brand_name) : 'Unknown Medication';
                    
                    addExistingMedication(
                        medicationName,
                        prescriptionMedication.dosage || '',
                        prescriptionMedication.frequency || '',
                        prescriptionMedication.duration || '',
                        prescriptionMedication.instructions || '',
                        prescriptionMedication.quantity_prescribed || '',
                        '0'
                    );
                });
            }
        });
    }
}

/**
 * Populate existing lab tests from medical report data
 */
function populateExistingLabTests() {
    // This function will be populated with actual data from the Blade template
    if (typeof window.medicalReportData !== 'undefined' && window.medicalReportData.labTestRequests) {
        const labTestsData = window.medicalReportData.labTestRequests;
        
        if (!labTestsData || labTestsData.length === 0) return;

        labTestsData.forEach((labTest, index) => {
            addExistingLabTest(
                labTest.test_name || '',
                labTest.test_type || 'other',
                labTest.priority || 'routine',
                labTest.preferred_date ? labTest.preferred_date.split('T')[0] : '',
                labTest.clinical_notes || ''
            );
        });
    }
}

/**
 * Helper function to safely escape HTML values
 */
function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return String(unsafe)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

/**
 * Add existing medication to the form
 */
function addExistingMedication(medicationName, dosage, frequency, duration, instructions = '', quantity = '', refills = '0') {
    prescriptionMedicationCount++;

    const container = document.getElementById('prescription-medications');
    const noMessage = document.getElementById('no-medications-message');

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
    if (noMessage) noMessage.classList.add('hidden');
}

/**
 * Add existing lab test to the form
 */
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
    if (noMessage) noMessage.classList.add('hidden');
}

/**
 * Initialize prescription management
 */
function initializePrescriptionManagement() {
    const existingMedications = document.querySelectorAll('.prescription-medication');
    if (existingMedications.length > 0) {
        prescriptionMedicationCount = existingMedications.length;
    }
    updatePrescriptionDisplay();
    updatePrescriptionSummary();
}

/**
 * Add new prescription medication
 */
function addPrescriptionMedication() {
    prescriptionMedicationCount++;

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
    if (noMessage) noMessage.classList.add('hidden');
    updatePrescriptionSummary();
}

/**
 * Remove prescription medication
 */
function removePrescriptionMedication(id) {
    const element = document.getElementById(`prescription-${id}`);
    if (element) {
        element.remove();
        updatePrescriptionDisplay();
        updatePrescriptionSummary();
    }
}

/**
 * Update prescription display
 */
function updatePrescriptionDisplay() {
    const container = document.getElementById('prescription-medications');
    const noMessage = document.getElementById('no-medications-message');

    if (container && noMessage) {
        if (container.children.length === 0) {
            noMessage.classList.remove('hidden');
        } else {
            noMessage.classList.add('hidden');
        }
    }
}

/**
 * Update prescription summary
 */
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

/**
 * Initialize lab test management
 */
function initializeLabTestManagement() {
    const existingLabTests = document.querySelectorAll('.lab-test-request');
    if (existingLabTests.length > 0) {
        labTestRequestCount = existingLabTests.length;
    }
    updateLabTestDisplay();
    updateLabTestSummary();
}

/**
 * Add lab test request
 */
function addLabTestRequest() {
    labTestRequestCount++;

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
    if (noMessage) noMessage.classList.add('hidden');
    updateLabTestSummary();
}

/**
 * Remove lab test request
 */
function removeLabTestRequest(id) {
    const element = document.getElementById(`lab-test-${id}`);
    if (element) {
        element.remove();
        updateLabTestDisplay();
        updateLabTestSummary();
    }
}

/**
 * Update lab test display
 */
function updateLabTestDisplay() {
    const container = document.getElementById('lab-test-requests');
    const noMessage = document.getElementById('no-lab-tests-message');

    if (container && noMessage) {
        if (container.children.length === 0) {
            noMessage.classList.remove('hidden');
        } else {
            noMessage.classList.add('hidden');
        }
    }
}

/**
 * Update lab test summary
 */
function updateLabTestSummary() {
    const labTests = document.querySelectorAll('.lab-test-request');
    let summary = '';

    labTests.forEach((test, index) => {
        const name = test.querySelector('[name*="[test_name]"]')?.value || '';
        const type = test.querySelector('[name*="[test_type]"]')?.value || '';
        const priority = test.querySelector('[name*="[priority]"]')?.value || '';

        if (name || type) {
            summary += `${index + 1}. ${name} (${type})${priority !== 'routine' ? ' - ' + priority.toUpperCase() : ''}\n`;
        }
    });

    const hiddenField = document.getElementById('lab_tests_ordered_hidden');
    if (hiddenField) {
        hiddenField.value = summary;
    }
}

/**
 * Update form progress
 */
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

    updateSectionStatus('patient', ['title', 'consultation_date', 'report_type']);
    updateSectionStatus('vitals', [
        'vital_signs[blood_pressure]', 'vital_signs[temperature]',
        'vital_signs[heart_rate]', 'vital_signs[weight]',
        'vital_signs[respiratory_rate]', 'vital_signs[oxygen_saturation]',
        'vital_signs[height]'
    ]);
    updateSectionStatus('assessment', ['chief_complaint', 'physical_examination']);
    updateSectionStatus('diagnosis', ['assessment_diagnosis', 'treatment_plan']);

    updateVitalsCompletion();
}

/**
 * Update vitals completion
 */
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

/**
 * Update section status
 */
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

/**
 * Apply preset templates
 */
function applyPreset(presetType) {
    switch (presetType) {
        case 'routine-checkup':
            document.querySelector('[name="title"]').value = 'Routine Health Check-up';
            document.querySelector('[name="report_type"]').value = 'Consultation';
            document.querySelector('[name="chief_complaint"]').value = 'Routine health examination and wellness check';
            fillNormalVitals();
            break;
        case 'follow-up':
            document.querySelector('[name="title"]').value = 'Follow-up Visit';
            document.querySelector('[name="report_type"]').value = 'Follow-up';
            document.querySelector('[name="chief_complaint"]').value = 'Follow-up visit for ongoing condition';
            break;
        case 'emergency':
            document.querySelector('[name="title"]').value = 'Emergency Consultation';
            document.querySelector('[name="report_type"]').value = 'Emergency';
            document.querySelector('[name="chief_complaint"]').value = 'Urgent medical condition requiring immediate attention';
            break;
    }
    updateFormProgress();
    showNotification(`${presetType.replace('-', ' ')} preset applied`, 'success');
}

/**
 * Fill normal vital signs
 */
function fillNormalVitals() {
    document.querySelector('[name="vital_signs[blood_pressure]"]').value = '120/80';
    document.querySelector('[name="vital_signs[temperature]"]').value = '98.6';
    document.querySelector('[name="vital_signs[heart_rate]"]').value = '72';
    document.querySelector('[name="vital_signs[respiratory_rate]"]').value = '16';
    document.querySelector('[name="vital_signs[oxygen_saturation]"]').value = '98';
    document.querySelector('[name="vital_signs[height]"]').value = '170';
    document.querySelector('[name="vital_signs[weight]"]').value = '70';
    calculateBMI();
    updateFormProgress();
    showNotification('Normal vital signs filled', 'success');
}

/**
 * Fill pediatric vital signs
 */
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

/**
 * Fill geriatric vital signs
 */
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

/**
 * Calculate BMI
 */
function calculateBMI() {
    const heightField = document.querySelector('[name="vital_signs[height]"]');
    const weightField = document.querySelector('[name="vital_signs[weight]"]');
    const bmiValue = document.getElementById('bmi-value');
    const bmiCategory = document.getElementById('bmi-category');

    if (!heightField || !weightField || !bmiValue || !bmiCategory) return;

    const height = parseFloat(heightField.value);
    const weight = parseFloat(weightField.value);

    if (height > 0 && weight > 0) {
        const heightInMeters = height > 3 ? height / 100 : height;
        const bmi = weight / (heightInMeters * heightInMeters);

        bmiValue.textContent = bmi.toFixed(1);

        if (bmi < 18.5) {
            bmiCategory.textContent = 'Underweight';
            bmiCategory.className = 'text-sm text-blue-600 dark:text-blue-400';
        } else if (bmi < 25) {
            bmiCategory.textContent = 'Normal';
            bmiCategory.className = 'text-sm text-green-600 dark:text-green-400';
        } else if (bmi < 30) {
            bmiCategory.textContent = 'Overweight';
            bmiCategory.className = 'text-sm text-yellow-600 dark:text-yellow-400';
        } else {
            bmiCategory.textContent = 'Obese';
            bmiCategory.className = 'text-sm text-red-600 dark:text-red-400';
        }
    } else {
        bmiValue.textContent = '--';
        bmiCategory.textContent = '--';
        bmiCategory.className = 'text-sm text-gray-600 dark:text-gray-400';
    }
}

/**
 * Toggle section visibility
 */
function toggleSection(sectionId) {
    const content = document.getElementById(`${sectionId}-content`);
    const toggle = document.getElementById(`${sectionId}-toggle`);

    if (content && toggle) {
        if (content.style.maxHeight && content.style.maxHeight !== 'auto') {
            content.style.maxHeight = 'auto';
            toggle.classList.remove('fa-chevron-down');
            toggle.classList.add('fa-chevron-up');
        } else {
            content.style.maxHeight = '0px';
            toggle.classList.remove('fa-chevron-up');
            toggle.classList.add('fa-chevron-down');
        }
    }
}

/**
 * Setup auto-save functionality
 */
function setupAutoSave() {
    const form = document.querySelector('form');
    if (!form) return;

    autoSaveInterval = setInterval(() => {
        // Placeholder for auto-save logic
        // In production, this would save to localStorage or send AJAX request
    }, 30000);
}

/**
 * Setup keyboard shortcuts
 */
function setupKeyboardShortcuts() {
    document.addEventListener('keydown', function(event) {
        if (event.ctrlKey) {
            switch (event.key) {
                case 's':
                    event.preventDefault();
                    const draftBtn = document.querySelector('button[name="status"][value="draft"]');
                    if (draftBtn) draftBtn.click();
                    break;
                case 'Enter':
                    event.preventDefault();
                    const completedBtn = document.querySelector('button[name="status"][value="completed"]');
                    if (completedBtn) completedBtn.click();
                    break;
                case 'p':
                    event.preventDefault();
                    previewReport();
                    break;
            }
        }
        
        // Alt+V for voice input
        if (event.altKey && event.key === 'v') {
            event.preventDefault();
            currentVoiceField = document.activeElement;
            toggleVoiceInput();
        }

        // Alt+T for tips
        if (event.altKey && event.key === 't') {
            event.preventDefault();
            showQuickTips();
        }
    });
}

/**
 * Preview report (placeholder)
 */
function previewReport() {
    showNotification('Report preview functionality is under development.', 'info');
}

/**
 * Show quick tips modal
 */
function showQuickTips() {
    const modal = document.getElementById('quick-tips-modal');
    if (modal) modal.classList.remove('hidden');
}

/**
 * Hide quick tips modal
 */
function hideQuickTips() {
    const modal = document.getElementById('quick-tips-modal');
    if (modal) modal.classList.add('hidden');
}

/**
 * Show form errors
 */
function showFormErrors(errors) {
    const errorSection = document.getElementById('form-errors-section');
    const errorList = document.getElementById('form-errors-list');

    if (!errorSection || !errorList) return;

    errorList.innerHTML = '';
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
    errorSection.scrollIntoView({ behavior: 'smooth' });
}

/**
 * Hide form errors
 */
function hideFormErrors() {
    const errorSection = document.getElementById('form-errors-section');
    if (errorSection) errorSection.classList.add('hidden');
}

/**
 * Show notification toast
 */
function showNotification(message, type = 'info', duration = 3000) {
    const container = document.createElement('div');
    container.className = `fixed top-5 right-5 p-4 rounded-lg shadow-xl text-white z-[100] transition-all duration-300 transform translate-x-full`;

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

    setTimeout(() => {
        container.classList.remove('translate-x-full');
        container.classList.add('translate-x-0');
    }, 100);

    setTimeout(() => {
        container.classList.add('translate-x-full');
        container.classList.remove('translate-x-0');
        setTimeout(() => container.remove(), 300);
    }, duration);
}

/**
 * Toggle all sections
 */
function toggleAllSections() {
    const sections = ['patient-info', 'vital-signs', 'clinical-assessment', 'diagnosis-treatment', 'follow-up-notes'];
    const firstSection = document.getElementById('patient-info-content');
    const isCollapsed = firstSection && firstSection.style.maxHeight === '0px';

    sections.forEach(sectionId => {
        const content = document.getElementById(`${sectionId}-content`);
        const toggle = document.getElementById(`${sectionId}-toggle`);

        if (content && toggle) {
            if (isCollapsed) {
                content.style.maxHeight = '1000px';
                toggle.classList.remove('fa-chevron-down');
                toggle.classList.add('fa-chevron-up');
            } else {
                content.style.maxHeight = '0px';
                toggle.classList.remove('fa-chevron-up');
                toggle.classList.add('fa-chevron-down');
            }
        }
    });

    showNotification(isCollapsed ? 'All sections expanded' : 'All sections collapsed', 'info');
}

/**
 * Clear form confirmation
 */
function clearForm() {
    if (confirm('Are you sure you want to clear all form fields? This action cannot be undone.')) {
        clearAllFields();
    }
}

/**
 * Toggle voice input
 */
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
            const btn = document.getElementById('voice-input-btn');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-stop mr-2"></i>Stop Voice';
                btn.classList.add('speech-indicator');
            }
            showNotification('Voice input started. Click on any text field and speak.', 'info');
        };

        recognition.onend = () => {
            const btn = document.getElementById('voice-input-btn');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-microphone mr-2"></i>Voice Input';
                btn.classList.remove('speech-indicator');
            }
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

// Event Listeners for form changes
document.addEventListener('input', function(e) {
    if (e.target.name && e.target.name.includes('prescriptions[')) {
        updatePrescriptionSummary();
    }
    if (e.target.name && e.target.name.includes('lab_tests[')) {
        updateLabTestSummary();
    }
    if (e.target.name && e.target.name.includes('vital_signs[')) {
        calculateBMI();
        updateVitalsCompletion();
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
