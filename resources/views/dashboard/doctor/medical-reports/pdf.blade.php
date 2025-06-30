<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Report - {{ $medicalReport->patient->name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
            background-color: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .clinic-name {
            font-size: 28px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 20px;
            color: #6b7280;
            font-weight: 500;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .info-section {
            text-align: center;
        }
        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }
        .info-value {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }
        .info-sub {
            font-size: 14px;
            color: #6b7280;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        .content-box {
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background-color: #f9fafb;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .chief-complaint {
            background-color: #dbeafe;
            border-color: #93c5fd;
            color: #1e40af;
        }
        .diagnosis {
            background-color: #dcfce7;
            border-color: #86efac;
            color: #166534;
        }
        .treatment {
            background-color: #dbeafe;
            border-color: #93c5fd;
            color: #1e40af;
        }
        .allergies {
            background-color: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
        }
        .medications {
            background-color: #fef3c7;
            border-color: #fcd34d;
            color: #92400e;
        }
        .follow-up {
            background-color: #ede9fe;
            border-color: #c4b5fd;
            color: #7c3aed;
        }
        .vitals-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-top: 15px;
        }
        .vital-box {
            padding: 12px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            text-align: center;
            background-color: #f9fafb;
        }
        .vital-label {
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .vital-value {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }
        .two-column {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        @media print {
            body {
                margin: 0;
                padding: 15px;
            }
            .header {
                margin-bottom: 20px;
            }
            .section {
                margin-bottom: 20px;
                page-break-inside: avoid;
            }
            .vitals-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>    <!-- Header -->
    <div class="header">
        <div class="clinic-name">Medical Clinic</div>
        @if($medicalReport->title)
            <div class="report-title">{{ $medicalReport->title }}</div>
            <div style="font-size: 16px; color: #9ca3af; margin-top: 5px;">Medical Report</div>
        @else
            <div class="report-title">Medical Report</div>
        @endif
    </div>

    <!-- Basic Information -->
    <div class="info-grid">
        <div class="info-section">
            <div class="info-label">Patient Information</div>
            <div class="info-value">{{ $medicalReport->patient->name }}</div>
            <div class="info-sub">{{ $medicalReport->patient->email }}</div>
        </div>
        <div class="info-section">
            <div class="info-label">Doctor</div>
            <div class="info-value">
                @if($medicalReport->doctor && $medicalReport->doctor->user)
                    {{ $medicalReport->doctor->user->name }}
                @else
                    No doctor assigned
                @endif
            </div>
            <div class="info-sub">
                @if($medicalReport->doctor)
                    {{ $medicalReport->doctor->specialization ?? 'General Practice' }}
                @endif
            </div>
        </div>
        <div class="info-section">
            <div class="info-label">Consultation Date</div>
            <div class="info-value">{{ $medicalReport->consultation_date->format('M d, Y') }}</div>
            <div class="info-sub">{{ $medicalReport->consultation_date->format('g:i A') }}</div>
        </div>
    </div>

    <!-- Chief Complaint -->
    <div class="section">
        <div class="section-title">Chief Complaint</div>
        <div class="content-box chief-complaint">
            {{ $medicalReport->chief_complaint }}
        </div>
    </div>

    <!-- History of Present Illness -->
    @if($medicalReport->history_of_present_illness)
    <div class="section">
        <div class="section-title">History of Present Illness</div>
        <div class="content-box">
            {{ $medicalReport->history_of_present_illness }}
        </div>
    </div>
    @endif

    <!-- Medical History Grid -->
    <div class="two-column">
        @if($medicalReport->past_medical_history)
        <div class="section">
            <div class="section-title">Past Medical History</div>
            <div class="content-box">
                {{ $medicalReport->past_medical_history }}
            </div>
        </div>
        @endif

        @if($medicalReport->medications)
        <div class="section">
            <div class="section-title">Current Medications</div>
            <div class="content-box">
                {{ $medicalReport->medications }}
            </div>
        </div>
        @endif
    </div>

    <div class="two-column">
        @if($medicalReport->allergies)
        <div class="section">
            <div class="section-title">Allergies</div>
            <div class="content-box allergies">
                {{ $medicalReport->allergies }}
            </div>
        </div>
        @endif

        @if($medicalReport->social_history)
        <div class="section">
            <div class="section-title">Social History</div>
            <div class="content-box">
                {{ $medicalReport->social_history }}
            </div>
        </div>
        @endif
    </div>

    @if($medicalReport->family_history)
    <div class="section">
        <div class="section-title">Family History</div>
        <div class="content-box">
            {{ $medicalReport->family_history }}
        </div>
    </div>
    @endif

    <!-- Vital Signs -->
    @if($medicalReport->vital_signs)
    <div class="section">
        <div class="section-title">Vital Signs</div>
        @php
            $vitalSigns = is_array($medicalReport->vital_signs)
                ? $medicalReport->vital_signs
                : json_decode($medicalReport->vital_signs, true);
        @endphp
        <div class="vitals-grid">
            @if(isset($vitalSigns['blood_pressure']) && $vitalSigns['blood_pressure'])
            <div class="vital-box">
                <div class="vital-label">Blood Pressure</div>
                <div class="vital-value">{{ $vitalSigns['blood_pressure'] }}</div>
            </div>
            @endif

            @if(isset($vitalSigns['heart_rate']) && $vitalSigns['heart_rate'])
            <div class="vital-box">
                <div class="vital-label">Heart Rate</div>
                <div class="vital-value">{{ $vitalSigns['heart_rate'] }} bpm</div>
            </div>
            @endif

            @if(isset($vitalSigns['temperature']) && $vitalSigns['temperature'])
            <div class="vital-box">
                <div class="vital-label">Temperature</div>
                <div class="vital-value">{{ $vitalSigns['temperature'] }}°F</div>
            </div>
            @endif

            @if(isset($vitalSigns['oxygen_saturation']) && $vitalSigns['oxygen_saturation'])
            <div class="vital-box">
                <div class="vital-label">O2 Saturation</div>
                <div class="vital-value">{{ $vitalSigns['oxygen_saturation'] }}%</div>
            </div>
            @endif

            @if(isset($vitalSigns['weight']) && $vitalSigns['weight'])
            <div class="vital-box">
                <div class="vital-label">Weight</div>
                <div class="vital-value">{{ $vitalSigns['weight'] }} lbs</div>
            </div>
            @endif

            @if(isset($vitalSigns['height']) && $vitalSigns['height'])
            <div class="vital-box">
                <div class="vital-label">Height</div>
                <div class="vital-value">{{ $vitalSigns['height'] }} ft</div>
            </div>
            @endif

            @if(isset($vitalSigns['respiratory_rate']) && $vitalSigns['respiratory_rate'])
            <div class="vital-box">
                <div class="vital-label">Respiratory Rate</div>
                <div class="vital-value">{{ $vitalSigns['respiratory_rate'] }} /min</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Physical Examination -->
    @if($medicalReport->physical_examination)
    <div class="section">
        <div class="section-title">Physical Examination</div>
        <div class="content-box">
            {{ $medicalReport->physical_examination }}
        </div>
    </div>
    @endif

    <!-- Diagnosis -->
    <div class="section">
        <div class="section-title">Diagnosis</div>
        <div class="content-box diagnosis">
            {{ $medicalReport->assessment_diagnosis }}
        </div>
    </div>

    <!-- Treatment Plan -->
    <div class="section">
        <div class="section-title">Treatment Plan</div>
        <div class="content-box treatment">
            {{ $medicalReport->treatment_plan }}
        </div>
    </div>

    <!-- Medications Prescribed -->
    @if($medicalReport->medications_prescribed)
    <div class="section">
        <div class="section-title">Medications Prescribed</div>
        <div class="content-box medications">
            {{ $medicalReport->medications_prescribed }}
        </div>
    </div>
    @endif

    <!-- Follow-up Instructions -->
    @if($medicalReport->follow_up_instructions)
    <div class="section">
        <div class="section-title">Follow-up Instructions</div>
        <div class="content-box follow-up">
            {{ $medicalReport->follow_up_instructions }}
            @if($medicalReport->follow_up_date)
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #c4b5fd;">
                    <strong>Next appointment: {{ $medicalReport->follow_up_date->format('M d, Y') }}</strong>
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Report Type -->
    <div class="section">
        <div class="section-title">Report Type</div>
        <div class="content-box">
            {{ $medicalReport->report_type }}
        </div>
    </div>

    <!-- Lab Tests Ordered -->
    @if($medicalReport->lab_tests_ordered)
    <div class="section">
        <div class="section-title">Lab Tests Ordered</div>
        <div class="content-box">
            {{ $medicalReport->lab_tests_ordered }}
        </div>
    </div>
    @endif

    <!-- Imaging Studies -->
    @if($medicalReport->imaging_studies)
    <div class="section">
        <div class="section-title">Imaging Studies</div>
        <div class="content-box">
            {{ $medicalReport->imaging_studies }}
        </div>
    </div>
    @endif

    <!-- Priority Level -->
    @if($medicalReport->priority_level)
    <div class="section">
        <div class="section-title">Priority Level</div>
        <div class="content-box">
            {{ ucfirst($medicalReport->priority_level) }}
        </div>
    </div>
    @endif

    <!-- Follow Up Required -->
    <div class="section">
        <div class="section-title">Follow Up Required</div>
        <div class="content-box">
            {{ $medicalReport->follow_up_required ? 'Yes' : 'No' }}
        </div>
    </div>

    <!-- Status -->
    <div class="section">
        <div class="section-title">Status</div>
        <div class="content-box">
            {{ ucfirst($medicalReport->status) }}
        </div>
    </div>

    <!-- Completed At -->
    @if($medicalReport->completed_at)
    <div class="section">
        <div class="section-title">Completed At</div>
        <div class="content-box">
            {{ $medicalReport->completed_at->format('M d, Y g:i A') }}
        </div>
    </div>
    @endif

    <!-- Additional Notes -->
    @if($medicalReport->additional_notes)
    <div class="section">
        <div class="section-title">Additional Notes</div>
        <div class="content-box">
            {{ $medicalReport->additional_notes }}
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>Report created on:</strong> {{ $medicalReport->created_at->format('M d, Y \a\t g:i A') }}</p>
        @if($medicalReport->updated_at != $medicalReport->created_at)
            <p><strong>Last updated on:</strong> {{ $medicalReport->updated_at->format('M d, Y \a\t g:i A') }}</p>
        @endif
        <p style="margin-top: 15px;">This is an official medical document. Please keep it for your records.</p>
    </div>
</body>
</html>
