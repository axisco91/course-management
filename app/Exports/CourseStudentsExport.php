<?php

namespace App\Exports;

use App\Models\Registration;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CourseStudentsExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function __construct($course_id){
        $this->course_id = $course_id;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = [];

        return collect($data);
    }

    public function headings(): array
    {
        return [

        ];
    }
}
