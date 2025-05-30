function printHealthProfile() {
    // Get the health profile content
    const contentElement = document.getElementById('health-profile-content');
    
    if (!contentElement) {
        alert('Health profile content not found');
        return;
    }
    
    // Debug: Log extracted data
    const extractedData = extractHealthData();
    console.log('=== PRINT DATA EXTRACTION ===');
    console.log('Allergies:', extractedData.allergies);
    console.log('Medications:', extractedData.medications);
    console.log('Conditions:', extractedData.conditions);
    console.log('Dietary:', extractedData.dietary);
    console.log('========================');
    
    // Create a professional medical document structure
    const printContent = createMedicalReport(contentElement);
    
    // Use Print.js to print the content
    printJS({
        printable: printContent,
        type: 'raw-html',
        style: `
            @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
            
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
                print-color-adjust: exact;
            }
            
            body {
                font-family: 'Times New Roman', 'Liberation Serif', serif;
                font-size: 12pt;
                line-height: 1.6;
                color: #000;
                background: white;
                margin: 0;
                padding: 0;
            }
            
            .medical-report {
                max-width: 8.5in;
                margin: 0 auto;
                padding: 0.75in;
                background: white;
            }
            
            .report-header {
                text-align: center;
                border-bottom: 3px solid #2563eb;
                padding-bottom: 20px;
                margin-bottom: 30px;
            }
            
            .report-title {
                font-size: 24pt;
                font-weight: bold;
                color: #1e40af;
                margin-bottom: 10px;
                letter-spacing: 1px;
            }
            
            .patient-info {
                font-size: 14pt;
                color: #374151;
                margin-bottom: 5px;
            }
            
            .report-date {
                font-size: 11pt;
                color: #6b7280;
                font-style: italic;
            }
            
            .section {
                margin-bottom: 25px;
                page-break-inside: avoid;
            }
            
            .section-header {
                background: #f8fafc;
                border-left: 4px solid #2563eb;
                padding: 10px 15px;
                margin-bottom: 15px;
                font-size: 14pt;
                font-weight: bold;
                color: #1e40af;
            }
            
            .section-content {
                padding: 0 10px;
            }
            
            .vitals-table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
            }
            
            .vitals-table th,
            .vitals-table td {
                border: 1px solid #d1d5db;
                padding: 10px;
                text-align: left;
            }
            
            .vitals-table th {
                background: #f3f4f6;
                font-weight: bold;
                font-size: 11pt;
            }
            
            .vitals-table td {
                font-size: 11pt;
            }
            
            .measurement-unit {
                font-size: 10pt;
                color: #6b7280;
                font-style: italic;
            }
            
            .info-table {
                width: 100%;
                border-collapse: collapse;
                margin: 15px 0;
            }
            
            .info-table th,
            .info-table td {
                border: 1px solid #e5e7eb;
                padding: 12px;
                text-align: left;
                vertical-align: top;
            }
            
            .info-table th {
                background: #f9fafb;
                font-weight: bold;
                width: 30%;
                font-size: 11pt;
            }
            
            .info-table td {
                font-size: 11pt;
            }
              .medical-list {
                background: #fefefe;
                border: 1px solid #e5e7eb;
                border-radius: 4px;
                padding: 10px;
                margin: 5px 0;
            }
            
            .medical-list ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }
            
            .medical-list li {
                padding: 4px 0;
                border-bottom: 1px dotted #d1d5db;
                position: relative;
                padding-left: 20px;
                font-size: 11pt;
                line-height: 1.4;
            }
            
            .medical-list li:before {
                content: "•";
                color: #dc2626;
                font-weight: bold;
                position: absolute;
                left: 0;
                top: 4px;
            }
            
            .medical-list li:last-child {
                border-bottom: none;
            }
            
            .status-badge {
                display: inline-block;
                padding: 2px 6px;
                border-radius: 10px;
                font-size: 9pt;
                font-weight: bold;
                text-transform: uppercase;
            }
            
            .status-normal {
                background: #dcfce7;
                color: #166534;
                border: 1px solid #bbf7d0;
            }
            
            .status-warning {
                background: #fef3c7;
                color: #92400e;
                border: 1px solid #fde68a;
            }
            
            .status-danger {
                background: #fee2e2;
                color: #991b1b;
                border: 1px solid #fecaca;
            }
            
            .footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 2px solid #e5e7eb;
                text-align: center;
                font-size: 10pt;
                color: #6b7280;
            }
            
            .confidential {
                background: #fef2f2;
                border: 1px solid #fecaca;
                padding: 10px;
                margin: 20px 0;
                text-align: center;
                font-size: 10pt;
                color: #991b1b;
                font-weight: bold;
            }
            
            .icon {
                font-family: "Font Awesome 6 Free";
                font-weight: 900;
                margin-right: 8px;
            }
            
            @page {
                size: A4;
                margin: 0.5in;
            }
            
            @media print {
                body { -webkit-print-color-adjust: exact; }
                .medical-report { page-break-inside: avoid; }
                .section { page-break-inside: avoid; }
            }
        `,
        documentTitle: 'Health Profile - Medical Report',
        showModal: true,
        modalMessage: 'Generating professional medical report...'
    });
}

function createMedicalReport(sourceElement) {
    // Extract data from the page
    const data = extractHealthData();
    
    return `
        <div class="medical-report">
            <div class="report-header">
                <div class="report-title">HEALTH PROFILE REPORT</div>
                <div class="patient-info">Patient: ${data.patientName}</div>
                <div class="patient-info">DOB: ${data.dateOfBirth}</div>
                <div class="report-date">Report Generated: ${data.reportDate}</div>
            </div>
            
            <div class="section">
                <div class="section-header">
                    <span class="icon">👤</span> VITAL STATISTICS
                </div>
                <div class="section-content">
                    <table class="vitals-table">
                        <thead>
                            <tr>
                                <th>Measurement</th>
                                <th>Value</th>
                                <th>Status</th>
                                <th>Reference Range</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Blood Type</strong></td>
                                <td>${data.bloodType}</td>
                                <td><span class="status-badge status-normal">KNOWN</span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td><strong>Height</strong></td>
                                <td>${data.height} <span class="measurement-unit">cm</span></td>
                                <td><span class="status-badge status-normal">NORMAL</span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td><strong>Weight</strong></td>
                                <td>${data.weight} <span class="measurement-unit">kg</span></td>
                                <td><span class="status-badge ${data.bmiStatus}">${data.bmiCategory}</span></td>
                                <td>18.5 - 24.9 kg/m²</td>
                            </tr>
                            <tr>
                                <td><strong>BMI</strong></td>
                                <td>${data.bmi}</td>
                                <td><span class="status-badge ${data.bmiStatus}">${data.bmiCategory}</span></td>
                                <td>18.5 - 24.9</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">
                    <span class="icon">🏥</span> MEDICAL INFORMATION
                </div>
                <div class="section-content">
                    <table class="info-table">                        <tr>
                            <th>Known Allergies:</th>
                            <td>
                                ${formatMedicalList(data.allergies)}
                            </td>
                        </tr>
                        <tr>
                            <th>Current Medications:</th>
                            <td>
                                ${formatMedicalList(data.medications)}
                            </td>
                        </tr>
                        <tr>
                            <th>Medical Conditions:</th>
                            <td>
                                ${formatMedicalList(data.conditions)}
                            </td>
                        </tr>
                        <tr>
                            <th>Family History:</th>
                            <td>${data.familyHistory || 'Not specified'}</td>
                        </tr>
                    </table>
                </div>
            </div>
              <div class="section">
                <div class="section-header">
                    <span class="icon">🏃</span> LIFESTYLE FACTORS
                </div>
                <div class="section-content">
                    <table class="info-table">
                        <tr>
                            <th>Exercise Frequency:</th>
                            <td>${data.exercise || 'Not specified'}</td>
                        </tr>
                        <tr>
                            <th>Smoking Status:</th>
                            <td>${data.smoking || 'Not specified'}</td>
                        </tr>
                        <tr>
                            <th>Alcohol Consumption:</th>
                            <td>${data.alcohol || 'Not specified'}</td>
                        </tr>                        <tr>
                            <th>Dietary Restrictions:</th>
                            <td>
                                ${formatMedicalList(data.dietary)}
                            </td>
                        </tr>
                        ${data.lifestyleNotes ? `
                        <tr>
                            <th>Lifestyle Notes:</th>
                            <td>${data.lifestyleNotes}</td>
                        </tr>
                        ` : ''}
                    </table>
                </div>
            </div>
            
            <div class="section">
                <div class="section-header">
                    <span class="icon">📞</span> EMERGENCY CONTACT & INSURANCE
                </div>
                <div class="section-content">
                    <table class="info-table">
                        <tr>
                            <th colspan="2" style="background: #f3f4f6; font-weight: bold; text-align: center; padding: 12px;">Emergency Contact Information</th>
                        </tr>
                        <tr>
                            <th>Emergency Contact Name:</th>
                            <td>${data.emergencyContact || 'Not specified'}</td>
                        </tr>
                        <tr>
                            <th>Emergency Phone:</th>
                            <td>${data.emergencyPhone || 'Not specified'}</td>
                        </tr>
                        <tr>
                            <th>Relationship:</th>
                            <td>${data.emergencyRelation || 'Not specified'}</td>
                        </tr>
                        <tr>
                            <th colspan="2" style="background: #f3f4f6; font-weight: bold; text-align: center; padding: 12px;">Insurance Information</th>
                        </tr>
                        <tr>
                            <th>Insurance Provider:</th>
                            <td>${data.insuranceProvider || 'Not specified'}</td>
                        </tr>
                        <tr>
                            <th>Policy Number:</th>
                            <td>${data.insurancePolicy || 'Not specified'}</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            ${data.additionalNotes ? `
            <div class="section">
                <div class="section-header">
                    <span class="icon">📝</span> ADDITIONAL NOTES
                </div>
                <div class="section-content">
                    <p style="font-size: 11pt; line-height: 1.5;">${data.additionalNotes}</p>
                </div>
            </div>
            ` : ''}
            
            <div class="confidential">
                CONFIDENTIAL MEDICAL INFORMATION - This document contains private health information protected by law
            </div>
            
            <div class="footer">
                <p><strong>MediCare Health Platform</strong></p>
                <p>This report is generated electronically and is valid without signature</p>
                <p>For medical emergencies, contact your healthcare provider immediately</p>
            </div>
        </div>
    `;
}

function formatMedicalList(data) {
    try {
        if (!data || data === 'Not specified' || data === 'None reported' || data === 'None specified') {
            return '<span style="font-style: italic; color: #6b7280;">None reported</span>';
        }
        
        // If data is already an array
        if (Array.isArray(data)) {
            if (data.length === 0) return '<span style="font-style: italic; color: #6b7280;">None reported</span>';
            
            const listItems = data.map(item => `<li>${item}</li>`).join('');
            return `<div class="medical-list">
                        <ul>${listItems}</ul>
                    </div>`;
        }
        
        // If data is a string
        if (typeof data === 'string') {
            // If it contains semicolons, split into list
            if (data.includes(';')) {
                const items = data.split(';').map(item => item.trim()).filter(item => item);
                if (items.length === 0) return '<span style="font-style: italic; color: #6b7280;">None reported</span>';
                
                const listItems = items.map(item => `<li>${item}</li>`).join('');
                return `<div class="medical-list">
                            <ul>${listItems}</ul>
                        </div>`;
            }
            // Single item
            return `<div class="medical-list">
                        <ul><li>${data}</li></ul>
                    </div>`;
        }
        
        return '<span style="font-style: italic; color: #6b7280;">None reported</span>';
    } catch (e) {
        console.error('Error formatting medical list:', e);
        return '<span style="font-style: italic; color: #6b7280;">Error formatting data</span>';
    }
}

function extractListItems(sectionSelector) {
    try {
        const section = document.querySelector(sectionSelector);
        if (!section) return null;
        
        const listItems = section.querySelectorAll('li');
        if (listItems.length > 0) {
            return Array.from(listItems).map(li => {
                // Clone the li element and remove icon elements
                const clone = li.cloneNode(true);
                const icons = clone.querySelectorAll('i, .fas, .far, .fab');
                icons.forEach(icon => icon.remove());
                return clone.textContent.trim();
            }).filter(text => text);
        }
        
        return null;
    } catch (e) {
        console.error('Error extracting list items:', e);
        return null;
    }
}

function extractHealthData() {
    const data = {};
    
    // Extract patient name from meta tag
    const metaUser = document.querySelector('meta[name="user-name"]');
    data.patientName = metaUser ? metaUser.content : 'Current Patient';
    
    // Get today's date for report
    data.reportDate = new Date().toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    data.dateOfBirth = 'Not specified'; // Would need to be passed from backend
    
    // Extract vital statistics using data attributes and fallbacks
    data.bloodType = extractDataField('blood-type') || 'Not specified';
    data.height = extractDataField('height') || 'Not specified';
    data.weight = extractDataField('weight') || 'Not specified';
    
    // Extract BMI information
    const bmiInfo = extractBMIInfo();
    data.bmi = bmiInfo.bmi;
    data.bmiCategory = bmiInfo.category;
    data.bmiStatus = bmiInfo.status;    // Extract medical information from specific sections
    data.allergies = extractDataValue('allergies') || extractMedicalSection('Allergies') || extractListItems('[data-field="allergies"]');
    data.medications = extractDataValue('medications') || extractMedicalSection('Current Medications') || extractListItems('[data-field="medications"]');
    data.conditions = extractDataValue('medical-conditions') || extractMedicalSection('Medical Conditions') || extractListItems('[data-field="medical-conditions"]');
    data.familyHistory = extractDataValue('family-history') || extractTextFromSection('Family History');// Extract lifestyle information
    data.exercise = extractDataField('exercise-frequency') || extractLifestyleInfo('Exercise Frequency');
    data.smoking = extractDataField('smoking-status') || extractHabitInfo('Smoker');
    data.alcohol = extractDataField('alcohol-status') || extractHabitInfo('Alcohol Consumer');
    data.dietary = extractDataValue('dietary-restrictions') || extractMedicalSection('Dietary Restrictions') || extractListItems('[data-field="dietary-restrictions"]');
    data.lifestyleNotes = extractDataField('lifestyle-notes') || extractDataValue('lifestyle-notes') || extractTextFromSection('Lifestyle Notes');
    
    // Extract emergency contact information
    data.emergencyContact = extractDataField('emergency-contact-name') || extractContactInfo('emergency_contact_name') || extractFromSection('Emergency Contact', 'Name');
    data.emergencyPhone = extractDataField('emergency-contact-phone') || extractContactInfo('emergency_contact_phone') || extractFromSection('Emergency Contact', 'Phone');
    data.emergencyRelation = extractDataField('emergency-contact-relationship') || extractContactInfo('emergency_contact_relationship') || extractFromSection('Emergency Contact', 'Relationship');
    
    // Extract insurance information
    data.insuranceProvider = extractDataField('insurance-provider') || extractInsuranceInfo('insurance_provider') || extractFromSection('Insurance Information', 'Provider');
    data.insurancePolicy = extractDataField('insurance-policy') || extractInsuranceInfo('insurance_policy_number') || extractFromSection('Insurance Information', 'Policy Number');
      // Extract additional notes
    data.additionalNotes = extractDataField('additional-notes') || extractDataValue('additional-notes') || extractTextFromSection('Additional Notes');
    
    return data;
}

function extractDataField(fieldName) {
    try {
        const element = document.querySelector(`[data-field="${fieldName}"]`);
        if (element) {
            let value = element.textContent.trim();
            // Clean up common suffixes
            value = value.replace(/\s*(cm|kg)$/i, '');
            return value;
        }
        return null;
    } catch (e) {
        console.error('Error extracting data field:', e);
        return null;
    }
}

function extractDataValue(fieldName) {
    try {
        const element = document.querySelector(`[data-field="${fieldName}"]`);
        if (element && element.getAttribute('data-value')) {
            const value = element.getAttribute('data-value').trim();
            // For medical/dietary fields that contain semicolons, return as array
            if ((fieldName.includes('allergies') || fieldName.includes('medications') || 
                 fieldName.includes('conditions') || fieldName.includes('dietary')) && 
                value.includes(';')) {
                return value.split(';').map(item => item.trim()).filter(item => item);
            }
            return value;
        }
        return null;
    } catch (e) {
        console.error('Error extracting data value:', e);
        return null;
    }
}

function extractMedicalSection(sectionName) {
    try {
        // Find the section label
        const labels = Array.from(document.querySelectorAll('label'));
        const sectionLabel = labels.find(label => 
            label.textContent.trim() === sectionName
        );
        
        if (sectionLabel) {
            const container = sectionLabel.nextElementSibling;
            if (container) {
                // Check for list items - return as array to preserve list structure
                const listItems = container.querySelectorAll('li');
                if (listItems.length > 0) {
                    // Extract clean text from each li, removing icon content
                    return Array.from(listItems).map(li => {
                        // Clone the li element and remove icon elements
                        const clone = li.cloneNode(true);
                        const icons = clone.querySelectorAll('i, .fas, .far, .fab');
                        icons.forEach(icon => icon.remove());
                        return clone.textContent.trim();
                    }).filter(text => text);
                }
                
                // Check for paragraph content
                const paragraph = container.querySelector('p');
                if (paragraph) {
                    // Clone and remove icons
                    const clone = paragraph.cloneNode(true);
                    const icons = clone.querySelectorAll('i, .fas, .far, .fab');
                    icons.forEach(icon => icon.remove());
                    const text = clone.textContent.trim();
                    
                    // If it contains semicolons, split into array
                    if (text.includes(';')) {
                        return text.split(';').map(item => item.trim()).filter(item => item);
                    }
                    return [text];
                }
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting medical section:', e);
        return null;
    }
}

function extractTextFromSection(sectionName) {
    try {
        // Find by section header
        const headers = Array.from(document.querySelectorAll('h3, label'));
        const header = headers.find(h => h.textContent.includes(sectionName));
        
        if (header) {
            const container = header.closest('.bg-white, .dark\\:bg-gray-800');
            if (container) {
                const content = container.querySelector('p');
                if (content && !content.textContent.includes('Not specified') && !content.textContent.includes('No ')) {
                    return content.textContent.trim();
                }
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting text section:', e);
        return null;
    }
}

function extractLifestyleInfo(infoType) {
    try {
        const labels = Array.from(document.querySelectorAll('label'));
        const label = labels.find(l => l.textContent.includes(infoType));
        
        if (label) {
            const container = label.parentElement;
            const valueSpan = container.querySelector('span');
            if (valueSpan) {
                return valueSpan.textContent.trim();
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting lifestyle info:', e);
        return null;
    }
}

function extractHabitInfo(habitType) {
    try {
        const spans = Array.from(document.querySelectorAll('span'));
        const habitSpan = spans.find(span => span.textContent.includes(`${habitType}:`));
        
        if (habitSpan) {
            return habitSpan.textContent.replace(`${habitType}:`, '').trim();
        }
        return null;
    } catch (e) {
        console.error('Error extracting habit info:', e);
        return null;
    }
}

function extractContactInfo(fieldName) {
    try {
        // Look for emergency contact section
        const headers = Array.from(document.querySelectorAll('h3'));
        const emergencyHeader = headers.find(h => h.textContent.includes('Emergency Contact'));
        
        if (emergencyHeader) {
            const section = emergencyHeader.closest('.bg-white, .dark\\:bg-gray-800');
            if (section) {
                const paragraphs = section.querySelectorAll('p');
                for (let p of paragraphs) {
                    if (fieldName.includes('name') && p.previousElementSibling && p.previousElementSibling.textContent.includes('Name')) {
                        return p.textContent.trim();
                    }
                    if (fieldName.includes('phone') && p.previousElementSibling && p.previousElementSibling.textContent.includes('Phone')) {
                        return p.textContent.trim();
                    }
                    if (fieldName.includes('relationship') && p.previousElementSibling && p.previousElementSibling.textContent.includes('Relationship')) {
                        return p.textContent.trim();
                    }
                }
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting contact info:', e);
        return null;
    }
}

function extractInsuranceInfo(fieldName) {
    try {
        // Look for insurance section
        const headers = Array.from(document.querySelectorAll('h3'));
        const insuranceHeader = headers.find(h => h.textContent.includes('Insurance Information'));
        
        if (insuranceHeader) {
            const section = insuranceHeader.closest('.bg-white, .dark\\:bg-gray-800');
            if (section) {
                const paragraphs = section.querySelectorAll('p');
                for (let p of paragraphs) {
                    if (fieldName.includes('provider') && p.previousElementSibling && p.previousElementSibling.textContent.includes('Provider')) {
                        return p.textContent.trim();
                    }
                    if (fieldName.includes('policy') && p.previousElementSibling && p.previousElementSibling.textContent.includes('Policy Number')) {
                        return p.textContent.trim();
                    }
                }
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting insurance info:', e);
        return null;
    }
}

function extractFromSection(sectionName, fieldName) {
    try {
        const headers = Array.from(document.querySelectorAll('h3'));
        const header = headers.find(h => h.textContent.includes(sectionName));
        
        if (header) {
            const section = header.closest('.bg-white, .dark\\:bg-gray-800');
            if (section) {
                const labels = section.querySelectorAll('p');
                for (let label of labels) {
                    if (label.textContent.includes(fieldName) && label.nextElementSibling) {
                        return label.nextElementSibling.textContent.trim();
                    }
                }
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting from section:', e);
        return null;
    }
}

function extractVitalStat(statName, suffix = '') {
    try {
        // First try to get data using data attributes
        const dataAttr = statName.toLowerCase().replace(' ', '-');
        const dataElement = document.querySelector(`[data-field="${dataAttr}"]`);
        
        if (dataElement) {
            let value = dataElement.textContent.trim();
            if (suffix && value.includes(suffix)) {
                value = value.replace(suffix, '').trim();
            }
            return value;
        }
        
        // Fallback to text search
        const statLabel = Array.from(document.querySelectorAll('p')).find(p => 
            p.textContent.trim() === statName
        );
        
        if (statLabel) {
            const valueElement = statLabel.nextElementSibling;
            if (valueElement) {
                let value = valueElement.textContent.trim();
                if (suffix && value.includes(suffix)) {
                    value = value.replace(suffix, '').trim();
                }
                return value;
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting vital stat:', e);
        return null;
    }
}

function extractBMIInfo() {
    try {
        // Try data attribute first
        const bmiElement = document.querySelector('[data-field="bmi"]');
        
        if (bmiElement) {
            const bmiText = bmiElement.textContent.trim();
            const parts = bmiText.split(' - ');
            
            const bmi = parts[0] || 'Not calculated';
            const category = parts[1] || 'Unknown';
            
            // Determine status based on BMI value
            const bmiNum = parseFloat(bmi);
            let status = 'status-normal';
            if (isNaN(bmiNum)) {
                status = 'status-normal';
            } else if (bmiNum < 18.5) {
                status = 'status-warning';
            } else if (bmiNum > 24.9) {
                status = 'status-warning';
            }
            
            return { bmi, category, status };
        }
        
        // Fallback to text search
        const bmiLabel = Array.from(document.querySelectorAll('p')).find(p => 
            p.textContent.trim() === 'BMI'
        );
        
        if (bmiLabel) {
            const bmiValue = bmiLabel.nextElementSibling;
            if (bmiValue) {
                const bmiText = bmiValue.textContent.trim();
                const parts = bmiText.split(' - ');
                
                const bmi = parts[0] || 'Not calculated';
                const category = parts[1] || 'Unknown';
                
                // Determine status based on BMI value
                const bmiNum = parseFloat(bmi);
                let status = 'status-normal';
                if (isNaN(bmiNum)) {
                    status = 'status-normal';
                } else if (bmiNum < 18.5) {
                    status = 'status-warning';
                } else if (bmiNum > 24.9) {
                    status = 'status-warning';
                }
                
                return { bmi, category, status };
            }
        }
        return { bmi: 'Not calculated', category: 'Unknown', status: 'status-normal' };
    } catch (e) {
        console.error('Error extracting BMI:', e);
        return { bmi: 'Not calculated', category: 'Unknown', status: 'status-normal' };
    }
}

function extractMedicalList(sectionName) {
    try {
        // Find the section by label
        const label = Array.from(document.querySelectorAll('label')).find(l => 
            l.textContent.includes(sectionName)
        );
        
        if (label) {
            // Find the container after the label
            const container = label.nextElementSibling;
            if (container) {
                // Look for list items
                const listItems = container.querySelectorAll('li');
                if (listItems.length > 0) {
                    return Array.from(listItems).map(li => {
                        // Remove icon text and get clean text
                        return li.textContent.replace(/^\s*[^\s\w]+\s*/, '').trim();
                    }).join(';');
                } else {
                    // Look for single paragraph
                    const paragraph = container.querySelector('p');
                    if (paragraph) {
                        return paragraph.textContent.replace(/^\s*[^\s\w]+\s*/, '').trim();
                    }
                }
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting medical list:', e);
        return null;
    }
}

function extractSingleField(fieldName) {
    try {
        const label = Array.from(document.querySelectorAll('label')).find(l => 
            l.textContent.includes(fieldName)
        );
        
        if (label) {
            const valueContainer = label.nextElementSibling;
            if (valueContainer) {
                const textElement = valueContainer.querySelector('p') || valueContainer;
                return textElement.textContent.trim();
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting single field:', e);
        return null;
    }
}

function extractLifestyleField(fieldName) {
    try {
        // Look for lifestyle section first
        const lifestyleSection = Array.from(document.querySelectorAll('h3')).find(h => 
            h.textContent.includes('Lifestyle') || h.textContent.includes('Personal')
        );
        
        if (lifestyleSection) {
            const section = lifestyleSection.closest('.bg-white, .dark\\:bg-gray-800');
            if (section) {
                const label = Array.from(section.querySelectorAll('label, span')).find(l => 
                    l.textContent.includes(fieldName)
                );
                
                if (label) {
                    const valueElement = label.nextElementSibling || label.parentElement.nextElementSibling;
                    if (valueElement) {
                        return valueElement.textContent.trim();
                    }
                }
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting lifestyle field:', e);
        return null;
    }
}

function extractEmergencyField(fieldName) {
    try {
        const emergencySection = Array.from(document.querySelectorAll('h3')).find(h => 
            h.textContent.includes('Emergency Contact')
        );
        
        if (emergencySection) {
            const section = emergencySection.closest('.bg-white, .dark\\:bg-gray-800');
            if (section) {
                const label = Array.from(section.querySelectorAll('label')).find(l => 
                    l.textContent.includes(fieldName)
                );
                
                if (label) {
                    const valueElement = label.nextElementSibling;
                    if (valueElement) {
                        return valueElement.textContent.trim();
                    }
                }
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting emergency field:', e);
        return null;
    }
}

function extractInsuranceField(fieldName) {
    try {
        const insuranceSection = Array.from(document.querySelectorAll('h3')).find(h => 
            h.textContent.includes('Insurance')
        );
        
        if (insuranceSection) {
            const section = insuranceSection.closest('.bg-white, .dark\\:bg-gray-800');
            if (section) {
                const label = Array.from(section.querySelectorAll('label')).find(l => 
                    l.textContent.includes(fieldName)
                );
                
                if (label) {
                    const valueElement = label.nextElementSibling;
                    if (valueElement) {
                        return valueElement.textContent.trim();
                    }
                }
            }
        }
        return null;
    } catch (e) {
        console.error('Error extracting insurance field:', e);
        return null;
    }
}
