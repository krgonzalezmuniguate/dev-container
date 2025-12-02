@php
    function getNestedValue($array, $key) {
        $keys = explode('.', $key);
        foreach ($keys as $innerKey) {
            if (isset($array[$innerKey])) {
                $array = $array[$innerKey];
            } else {
                return null;
            }
        }
        return $array;
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF</title>
    <style type="text/css">
        @page {
                margin: 30px 40px 30px 40px;
            }
            .page-break {
            page-break-after: always;
            }
        .tg  {border-collapse:collapse;border-spacing:0; width:90%; margin-top: 20px;}
        .tg td{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
        overflow:hidden;padding:10px 5px;word-break:normal;text-align:center;}
        .tg th{border-color:black;border-style:solid;border-width:1px;font-family:Arial, sans-serif;font-size:14px;
        font-weight:normal;overflow:hidden;padding:10px 5px;word-break:normal;}
        .tg .tg-0lax{text-align:left;vertical-align:top; width:20%}
        .tg .tg-1lax{text-align:center;vertical-align:center; width:20%}
        .tg .tg-nx8p{font-size:18px;text-align:center;vertical-align: middle; font-weight: bold;}
    </style>
</head>

<body>
    <center>
        <table  align="center" class="tg">
            <thead>
                <tr>
                    <td class="tg-1lax" rowspan="2">
                        <img src="{{ $logo }}" alt="" srcset="" height="60">
                    </td>
                    <td class="tg-nx8p" rowspan="2">
                        <div>{{ isset($type) ? 'REGISTRO DE CONTROL DE '.$type : 'REGISTRO DE CONTROL' }}</div>
                    </td>
                    <td class="tg-0lax">
                        <div></div>
                        <div></div>
                    </td>
                </tr>
                <tr>
                    <td class="tg-0lax">
                    <div>VERSIÓN:</div>
                    <div>{{ isset($version) ? $version : '0001' }}</div>
                    </td>
                </tr>
            </thead>
        </table>
    </center>
    <center>
        <table align="center" class="tg">
            <thead>
                <tr>
                    @foreach ($columns as $column)
                        @if (!in_array('hidden',$column))
                            <th>
                                <strong>
                                    {{ strtoupper($column['title']) }}
                                </strong>
                            </th>
                        @endif
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item )
                <tr>
                    @foreach ($columns as $column)
                        @if (!in_array('hidden',$column))
                            <td>{{ getNestedValue($item, $column['key']) }}</td>
                        @endif
                    @endforeach
                </tr>
                @empty
                <tr>
                    <td colspan="{{ intval(count($columns)) }}" align="center" >No hay data .....</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </center>
    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(500, 820, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
    </script>
</body>
</html>
