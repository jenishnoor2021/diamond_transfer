<?php

namespace App\Imports;

use App\Models\Diamond;
use App\Models\Location;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DiamondImport implements ToModel, WithStartRow
{
    protected $locationId;

    public function __construct()
    {
        $this->locationId = Location::where('name', 'Surat')->value('id');
    }

    public function startRow(): int
    {
        return 2;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $stockNo = trim($row[0]);
        $certiNo = trim($row[1]);

        // Duplicate check
        if (Diamond::where('stock_no', $stockNo)->where('certificate_no', $certiNo)->exists()) {
            return null; // skip record
        }

        do {
            $number = mt_rand(1000000000, 9999999999);
        } while (Diamond::where('barcode_number', $number)->exists());

        return new Diamond([
            'barcode_number' => $number,
            'stock_no' => trim($row[0]),
            'certificate_no' => trim($row[1]),
            'availability' => trim($row[2]),
            'shape' => trim($row[3]),
            'weight' => trim($row[4]),
            'color' => trim($row[5]),
            'clarity' => trim($row[6]),
            'fancy_color' => trim($row[7]),
            'fancy_color_intensity' => trim($row[8]),
            'fancy_color_overtone' => trim($row[9]),
            'cut_grade' => trim($row[10]),
            'polish' => trim($row[11]),
            'symmetry' => trim($row[12]),
            'fluorescence_intensity' => trim($row[13]),
            'measurements' => trim($row[14]),
            'depth_percent' => trim($row[15]),
            'table_percent' => trim($row[16]),
            'lab' => trim($row[17]),
            'price_per_carat' => trim($row[18]),
            'country' => trim($row[19]),
            'state' => trim($row[20]),
            'city' => trim($row[21]),
            'image_url' => trim($row[22]),
            'video_url' => trim($row[23]),
            'growth_type' => trim($row[24]),
            'location_id' => $this->locationId, // default Surat
            'status' => 'in_stock',
        ]);
    }
}
