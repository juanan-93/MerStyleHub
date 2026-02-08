<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Respuestas – {{ $questionnaireUser->questionnaire->title }}</title>
    <style>
        /* ===== Reset & Base ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #343434;
            line-height: 1.5;
        }

        /* ===== Header ===== */
        .header {
            background-color: #A08A7A;
            color: #ffffff;
            padding: 28px 35px;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .header p {
            font-size: 11px;
            opacity: 0.9;
        }

        /* ===== Meta info ===== */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .meta-table td {
            padding: 8px 14px;
            font-size: 11px;
            border: 1px solid #e0d6cf;
        }

        .meta-table .label {
            background-color: #f5f0ec;
            font-weight: 600;
            color: #6b5b50;
            width: 160px;
        }

        .meta-table .value {
            color: #343434;
        }

        /* ===== Questions ===== */
        .question-block {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }

        .question-header {
            background-color: #f5f0ec;
            padding: 10px 14px;
            border-left: 4px solid #A08A7A;
            margin-bottom: 0;
        }

        .question-number {
            display: inline-block;
            background-color: #A08A7A;
            color: #ffffff;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            text-align: center;
            line-height: 22px;
            font-size: 10px;
            font-weight: 700;
            margin-right: 8px;
        }

        .question-text {
            font-weight: 600;
            font-size: 12px;
            color: #4a3f37;
        }

        .question-type {
            font-size: 9px;
            color: #A08A7A;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: 30px;
            letter-spacing: 0.5px;
        }

        .answer-box {
            border: 1px solid #e0d6cf;
            border-top: none;
            padding: 12px 14px 12px 44px;
            background-color: #ffffff;
            font-size: 12px;
            color: #343434;
            min-height: 28px;
        }

        .answer-box .no-response {
            color: #999;
            font-style: italic;
        }

        .answer-box .selected-option {
            color: #A08A7A;
            font-weight: 600;
        }

        /* ===== Files list ===== */
        .files-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .files-list li {
            padding: 4px 0;
            font-size: 11px;
        }

        .files-list li::before {
            content: "📎 ";
        }

        .file-image {
            max-width: 140px;
            max-height: 100px;
            margin-top: 6px;
            border: 1px solid #e0d6cf;
            border-radius: 4px;
        }

        /* ===== Footer ===== */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999;
            padding: 10px 0;
            border-top: 1px solid #e0d6cf;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>{{ $questionnaireUser->questionnaire->title }}</h1>
        @if($questionnaireUser->questionnaire->description)
            <p>{{ $questionnaireUser->questionnaire->description }}</p>
        @endif
    </div>

    {{-- Meta --}}
    <table class="meta-table">
        <tr>
            <td class="label">Cliente</td>
            <td class="value">{{ $user->name }}</td>
            <td class="label">Email</td>
            <td class="value">{{ $user->email }}</td>
        </tr>
        <tr>
            <td class="label">Estado</td>
            <td class="value">Completado</td>
            <td class="label">Fecha completado</td>
            <td class="value">{{ $questionnaireUser->completed_at ? $questionnaireUser->completed_at->format('d/m/Y H:i') : 'N/A' }}</td>
        </tr>
    </table>

    {{-- Respuestas --}}
    @foreach($questions as $index => $question)
        @php
            $response = $responses->get($question->id);
        @endphp

        <div class="question-block">
            <div class="question-header">
                <span class="question-number">{{ $index + 1 }}</span>
                <span class="question-text">{{ $question->text }}</span>
                <div class="question-type">{{ $question->type }}</div>
            </div>

            <div class="answer-box">
                @if($response)
                    @if($question->type === 'text')
                        {{ $response->text_response ?? '' }}

                    @elseif($question->type === 'select' || $question->type === 'test')
                        @if($response->selectedOption)
                            <span class="selected-option">✔ {{ $response->selectedOption->text }}</span>
                        @else
                            <span class="no-response">Sin respuesta</span>
                        @endif

                    @elseif($question->type === 'file')
                        @php
                            $files = json_decode($response->text_response, true);
                        @endphp
                        @if(is_array($files) && count($files) > 0)
                            <ul class="files-list">
                                @foreach($files as $file)
                                    <li>
                                        {{ $file['name'] ?? 'Archivo' }}
                                        @if(isset($file['size']))
                                            ({{ number_format($file['size'] / 1024, 0) }} KB)
                                        @endif
                                        @if(str_starts_with($file['mime'] ?? '', 'image/'))
                                            @php
                                                $absPath = storage_path('app/public/' . $file['path']);
                                            @endphp
                                            @if(file_exists($absPath))
                                                <br>
                                                <img src="{{ $absPath }}" class="file-image" alt="{{ $file['name'] ?? '' }}">
                                            @endif
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="no-response">Sin archivos adjuntos</span>
                        @endif

                    @elseif($question->type === 'info')
                        <span class="no-response">Información leída</span>

                    @else
                        <span class="no-response">—</span>
                    @endif
                @else
                    <span class="no-response">Sin respuesta registrada</span>
                @endif
            </div>
        </div>
    @endforeach

    {{-- Footer --}}
    <div class="footer">
        MerStyleHub · Exportado el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
