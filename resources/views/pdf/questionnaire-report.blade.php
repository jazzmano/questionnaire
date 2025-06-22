<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>AI Act Assessment Report</title>
    <style>
        @page {
            margin: 2cm;
            size: A4;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2563eb;
        }
        
        .header h1 {
            font-size: 28px;
            color: #1e40af;
            margin: 0 0 10px 0;
            font-weight: 700;
        }
        
        .header .subtitle {
            font-size: 16px;
            color: #6b7280;
            margin: 0;
            font-weight: 400;
        }
        
        .report-meta {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border-left: 4px solid #2563eb;
        }
        
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .meta-item {
            margin-bottom: 8px;
        }
        
        .meta-label {
            font-weight: 600;
            color: #374151;
            display: inline-block;
            min-width: 100px;
        }
        
        .meta-value {
            color: #1f2937;
        }
        
        .assessment-result {
            background: {{ $session->final_node_key === 'your_system_is_an_AI_system' ? '#ecfdf5' : '#fef3c7' }};
            border: 2px solid {{ $session->final_node_key === 'your_system_is_an_AI_system' ? '#10b981' : '#f59e0b' }};
            border-radius: 10px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
        }
        
        .result-icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }
        
        .result-title {
            font-size: 22px;
            font-weight: 700;
            color: {{ $session->final_node_key === 'your_system_is_an_AI_system' ? '#065f46' : '#92400e' }};
            margin-bottom: 10px;
        }
        
        .result-description {
            font-size: 16px;
            color: {{ $session->final_node_key === 'your_system_is_an_AI_system' ? '#047857' : '#b45309' }};
            line-height: 1.5;
        }
        
        .section {
            margin-bottom: 35px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e40af;
            margin-bottom: 20px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .answer-card {
            background: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .question-text {
            font-weight: 600;
            color: #374151;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .answer-text {
            color: #1f2937;
            margin-bottom: 12px;
            padding: 8px 12px;
            background: #f3f4f6;
            border-radius: 6px;
            border-left: 3px solid #2563eb;
        }
        
        .justification {
            margin-top: 12px;
            padding: 12px;
            background: #f9fafb;
            border-radius: 6px;
            border-left: 3px solid #6b7280;
        }
        
        .justification-label {
            font-weight: 600;
            color: #4b5563;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .justification-text {
            color: #374151;
            font-style: italic;
            line-height: 1.5;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        
        .timestamp {
            color: #9ca3af;
            font-size: 14px;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-ai {
            background: #dbeafe;
            color: #1e40af;
        }
        
        .badge-not-ai {
            background: #fef3c7;
            color: #92400e;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>AI Act Assessment Report</h1>
        <p class="subtitle">European Union AI Act Compliance Assessment</p>
    </div>

    <div class="report-meta">
        <div class="meta-grid">
            <div>
                <div class="meta-item">
                    <span class="meta-label">Name:</span>
                    <span class="meta-value">{{ $identifier->name ?? 'Not provided' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Email:</span>
                    <span class="meta-value">{{ $identifier->email ?? 'Not provided' }}</span>
                </div>
            </div>
            <div>
                <div class="meta-item">
                    <span class="meta-label">Company:</span>
                    <span class="meta-value">{{ $identifier->company ?? 'Not provided' }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">System:</span>
                    <span class="meta-value">{{ $identifier->system_name ?? 'Not provided' }}</span>
                </div>
            </div>
        </div>
        <div style="margin-top: 15px;">
            <div class="meta-item">
                <span class="meta-label">Assessment Period:</span>
                <span class="meta-value timestamp">{{ \Carbon\Carbon::parse($session->started_at)->format('F j, Y \a\t g:i A') }} - {{ \Carbon\Carbon::parse($session->completed_at)->format('F j, Y \a\t g:i A') }}</span>
            </div>
        </div>
    </div>

    <div class="assessment-result">
        <span class="result-icon">
            @if($session->final_node_key === 'your_system_is_an_AI_system')
                ✓
            @else
                ⚠
            @endif
        </span>
        <div class="result-title">
            @if($session->final_node_key === 'your_system_is_an_AI_system')
                AI System Identified
            @else
                Not Subject to AI Act
            @endif
        </div>
        <div class="result-description">
            @if($session->final_node_key === 'your_system_is_an_AI_system')
                Your system qualifies as an AI system under Article 3(1) of the EU AI Act and is subject to its regulatory requirements.
            @else
                Based on your responses, this system does not qualify as an AI system under the EU AI Act and is therefore not subject to its regulatory requirements.
            @endif
        </div>
    </div>

    <div class="section">
        <h2 class="section-title">Assessment Details</h2>
        
        @foreach ($session->actions as $index => $action)
            <div class="answer-card">
                <div class="question-text">
                    Question {{ $index + 1 }}: {{ $action->node_question ?? ucfirst(str_replace('_', ' ', $action->node_key)) }}
                </div>
                
                <div class="answer-text">
                    <strong>Response:</strong> {{ $action->selected_option }}
                </div>
                
                @if($action->justification)
                    <div class="justification">
                        <div class="justification-label">Justification:</div>
                        <div class="justification-text">{{ $action->justification }}</div>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="footer">
        <p>This report was generated automatically based on your responses to the AI Act Assessment Tool.</p>
        <p>Report generated on {{ now()->format('F j, Y \a\t g:i A T') }}</p>
        <p><strong>Disclaimer:</strong> This assessment is for informational purposes only and does not constitute legal advice.</p>
    </div>
</body>
</html>