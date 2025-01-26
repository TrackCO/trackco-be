<?php

namespace App\Exports;

use App\Models\CarbonFootprint;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
class EmissionHistoriesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $emissionHistories;

    public function __construct($emissionHistories)
    {
        $this->emissionHistories = $emissionHistories;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->emissionHistories;
    }
    public function headings(): array
    {
        return [
            'Name',
            'Country',
            'Energy Emission',
            'Transportation Emission',
            'Lifestyle Emission',
            'Total Annual Emission',
            'Recorded At',
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->country->name ?? 'N/A',
            $row->energy_emission,
            $row->transportation_emission,
            $row->lifestyle_emission,
            $row->total_emission,
            \Carbon\Carbon::parse($row->created_at)->format('jS M, Y'),
        ];
    }
}
