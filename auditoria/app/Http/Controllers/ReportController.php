<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ExcelExport;
use Carbon\Carbon;
class ReportController extends Controller
{
    public function exportExcel(Request $request)
    {
        $request->validate([
            'columns' => 'required',
            'data' => 'required',
        ]);
        try {
            // return Excel::download(new ExcelExport($request->columns, $request->data), 'export.xlsx');
        } catch (\Throwable $th) {
            return response($th->getMessage());
        }
    }
    public function exportPDF(Request $request)
    {
        try {
            $path = public_path('img/dai.png');
            $imageData = file_get_contents($path);
            $base64 = base64_encode($imageData);
            $datos = [
                'items' => $request->data,
                'columns' => $request->columns,
                'logo' => 'data:image/png;base64,' . $base64
            ];
            if (isset($request->type)) {
                $datos['type'] = $request->type;
            }
            if (isset($request->version)) {
                $datos['version'] = $request->version;
            }
            $pdf = PDF::loadView('pdf.Default', $datos);
            $fecha = Carbon::now()->format('Y-m-d');
            return $pdf->download('export-'. $fecha . '.pdf');
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage(),
                'line' => $th->getLine()
            ]);
        }
    }
}
