<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject ?? 'Notificación' }}</title>
</head>
<body>
    @if(isset($header))
        <h1>{{ $header }}</h1>
    @endif

    @if(isset($greeting))
        <p>{{ $greeting }}</p>
    @endif

    @if(isset($introLines))
        @foreach($introLines as $line)
            <p>{{ $line }}</p>
        @endforeach
    @endif

    @if(isset($table))
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    @foreach($table['headers'] as $header)
                        <th style="padding: 8px; border: 1px solid #ddd; text-align: left;">{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($table['rows'] as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td style="padding: 8px; border: 1px solid #ddd;">{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(isset($actionUrl) && isset($actionText))
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center" style="background-color: #3490dc; border-radius: 8px; padding: 10px 18px;">
                    <a href="{{ $actionUrl }}" style="font-family: Arial, sans-serif; font-size: 16px; font-weight: bold; color: #ffffff; text-decoration: none; display: inline-block;">
                        {{ $actionText }}
                    </a>
                </td>
            </tr>
        </table>
    @endif

    @if(isset($outroLines))
        @foreach($outroLines as $line)
            <p>{{ $line }}</p>
        @endforeach
    @endif

    <p>Gracias,<br>
    <p>Atentamente: </p>
    {{ config('app.name') }}</p>
</body>
</html>
