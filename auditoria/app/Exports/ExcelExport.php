<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $columns = [], $data = [];
    public function __construct($columns, $data) {
        $this->columns = $columns;
        $this->data = $data;
    }

    public function view() : View {

        $columns = $this->columns;
        $data = $this->data;

        return view('Excel.Default',compact('columns','data'));
    }

}
