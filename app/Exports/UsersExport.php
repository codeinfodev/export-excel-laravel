<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class UsersExport implements FromCollection,WithHeadings,WithStyles,WithMapping,ShouldAutoSize
{
    
    /**
    * @return \Illuminate\Support\AfterSheet
    */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:D1'; // All headers A1:Z1
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }


    /**
    * @return \Illuminate\Support\WithMapping
    */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            date('M d, Y h:i A',strtotime($user->created_at)),
        ];
    }


    /**
    * @return \Illuminate\Support\WithStyles
    */
    public function styles(Worksheet $sheet)
    {
        return [
           1    => ['font' => ['bold' => true]],
        ];
    }


    /**
    * @return \Illuminate\Support\WithHeadings
    */
    public function headings(): array
    {
        return ["id", "name", "email",'created_at'];
    }


    /**
    * @return \Illuminate\Support\FromCollection
    */
    public function collection()
    {
        return User::select('id','name','email','created_at')->get();
    }

    
}
