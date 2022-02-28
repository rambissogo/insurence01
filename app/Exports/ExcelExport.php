<?php

namespace App\Exports;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; 
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
class ExcelExport implements FromCollection, WithHeadings,WithEvents
{
    protected $data;
    protected $head;
    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        
        return $this->head;
        
    }

    public function __construct($data,$head)
    {
        $this->data = $data;
        $this->head = $head;
       
    }
 
public function registerEvents(): array
{
    return [
        AfterSheet::class    => function(AfterSheet $event) {

            $event->sheet->getDelegate()->getStyle('A1:K1')
                            ->getFont()
                            ->setBold(true);

        },
    ];
}
}