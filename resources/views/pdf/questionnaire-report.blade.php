<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Questionnaire Report</title>
    <style>
        body { font-family: sans-serif; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        .section { margin-bottom: 20px; }
        .step { margin-bottom: 10px; }
        .label { font-weight: bold; }
    </style>
</head>
<body>
    <h1>AI System Assessment Report</h1>
    <div class="section">
        <p><span class="label">User:</span> {{ $session->user->name ?? 'Anonymous' }}</p>
        <p><span class="label">Started:</span> {{ $session->started_at }}</p>
        <p><span class="label">Completed:</span> {{ $session->completed_at }}</p>
        <p><span class="label">Final Result:</span> {{ $session->final_node_key }}</p>
    </div>

    <div class="section">
        <h2>Answers</h2>
        @foreach ($session->actions as $action)
            <div class="step">
                <p><span class="label">Question:</span> {{ $action->node_key }}</p>
                <p><span class="label">Selected:</span> {{ $action->selected_option }}</p>
                @if($action->justification)
                    <p><span class="label">Justification:</span> {{ $action->justification }}</p>
                @endif
            </div>
        @endforeach
    </div>
</body>
</html>
