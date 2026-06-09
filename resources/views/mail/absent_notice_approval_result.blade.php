<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Absent Notice Slip — {{ $data['status'] ?? 'Result' }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 24px;
            color: #1f2933;
        }
        .card {
            max-width: 520px;
            margin: 48px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }
        .banner {
            padding: 28px 24px;
            text-align: center;
            color: #fff;
            background: {{ ($data['success'] ?? 0) == 1 ? '#20BD67' : '#767F86' }};
        }
        .banner h1 {
            margin: 8px 0 0;
            font-size: 1.5rem;
            font-weight: 600;
        }
        .body {
            padding: 24px;
            line-height: 1.6;
        }
        .meta {
            color: #52606d;
            margin-top: 12px;
        }
        .portal-link {
            display: inline-block;
            margin-top: 20px;
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="banner">
            <h1>{{ $data['status'] ?? 'Result' }}</h1>
        </div>
        <div class="body">
            <p>{!! $data['message'] ?? '' !!}</p>
            @if (! empty($data['approved_by']))
                <p class="meta">
                    {{ ($data['status'] ?? '') === 'APPROVED' ? 'Approved by: ' : 'Disapproved by: ' }}{{ $data['approved_by'] }}
                </p>
            @endif
            @if (! empty($data['approved_date']))
                <p class="meta">
                    Date {{ ($data['status'] ?? '') === 'APPROVED' ? 'approved: ' : 'disapproved: ' }}{{ $data['approved_date'] }}
                </p>
            @endif
            <a class="portal-link" href="{{ url('/') }}">Go to Essex portal</a>
        </div>
    </div>
</body>
</html>
