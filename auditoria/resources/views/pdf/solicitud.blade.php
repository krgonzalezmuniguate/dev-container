<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Solicitud de Suministros #{{ $solicitud->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin-top: 150px; /* Adjust this to make space for the header */
        }

        /* HEADER & FOOTER STYLES */
        @page {
            margin: 30px 25px 45px 25px;
        }

        header {
            position: fixed;
            top: 0px;
            left: 0px;
            right: 0px;
            height: 100px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            text-align: center;
            font-size: 10px;
        }

        .header-content {
            width: 100%;
            padding: 10px 0;
            display: table;
        }

        .header-content > div {
            display: table-cell;
            border: 2px solid black;
            padding: 1rem;
            vertical-align: middle;
        }

        .header-logo {
            width: 20%;
            padding-left: 20px;
        }

        .header-title {
            width: 60%;
            font-size: 16px;
            font-weight: bold;
        }

        .header-version {
            width: 20%;
            text-align: center;
            padding-right: 5px;
            font-size: 12px;
            font-weight: bold;
        }

        .signatures-table {
            margin-top: 40px;
        }

        /* GENERAL STYLES */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            /* border: 1px solid black; */
        }
        th, td {
            padding: 6px;
            text-align: left;
        }
        .section-title {
            margin-top: 20px;
            font-weight: bold;
        }
        .signatures-table td {
            text-align: center;
            vertical-align: top;
            padding: 10px;
        }
        .qr-img {
            width: 120px;
            height: 120px;
        }
        .mt {
            margin-top: 10rem;
        }
        .font {
            font-size: 16px;
        }

    </style>
</head>
<body>

    <header>
        <div class="header-content">
            <div class="header-logo">
                <img src="{{ public_path('img/dai.png') }}" style="height: 60px;">
            </div>
            <div class="header-title">
                REGISTRO DE SOLICITUD DE SUMINISTROS
            </div>
            <div class="header-version">
                Versión: <br/>
                0001
            </div>
        </div>
    </header>

    <footer>
        Página
    </footer>

    <main>
        <p class="font"><strong>No. de Solicitud:</strong> {{ $solicitud->id }}</p>
        <p class="font"><strong>Fecha:</strong> {{ $solicitud->created_at->format('d/m/Y') }}</p>
        <div class="section-title font">Detalles de la Solicitud:</div>
        <p class="font">{{ $solicitud->description }}</p>
        <div class="section-title mt">Verificaciones:</div>
        <table class="signatures-table">
            <tr>
                <td>
                    <strong>Solicitado por:</strong><br>
                    {{ $user->fullname ?? 'DESCONOCIDO' }}<br>
                    <img src="{{ $qr }}" alt="Código QR de la solicitud"><br>
                </td>
                <td>
                    <strong>Autorizado por:</strong><br>
                    {{ $jefe->fullname ?? 'DESCONOCIDO' }}<br>
                    <img src="{{ $qr_chief }}" alt="Código QR de la solicitud"><br><br>
                </td>
            </tr>
        </table>
    </main>

    <script type="text/php">
    if (isset($pdf)) {
        $font = $fontMetrics->get_font("Arial", "normal");
        $size = 10;
        $y = 800; // Posición vertical
        $x = 525; // Posición horizontal

        // Obtener el número total de páginas y la página actual
        $page_count = $pdf->get_page_count();
        $page_num = $pdf->get_page_number();

        // Construir la cadena de texto completa
        $text = "Página " . $page_num . " de " . $page_count;

        // Escribir el texto en el PDF
        $pdf->page_text($x, $y, $text, $font, $size, array(0, 0, 0));
    }
</script>
</body>
</html>
