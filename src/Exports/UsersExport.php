<?php

namespace Ovic\Framework;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class UsersExport implements FromView, WithEvents
{
    public $users = [];

    public function __construct( $donvi_id = '' )
    {
        $user = Auth::user();
        if ( class_exists(\Modules\Doituong\Entities\DTTDCanhan::class) ) {
            $condition = \Modules\Doituong\Entities\DTTDCanhan::where('id', '>', 0);
            if ( empty($donvi_id) ) {
                $donvi = Donvi::getDonvi(true);
                unset($donvi[$user->donvi_id]);
                $condition = $condition->where(function ( $query ) use ( $donvi ) {
                    $query->whereIn('donvi_id', array_keys($donvi));
                });
            } else {
                $condition = $condition->where('donvi_id', $donvi_id);
            }
            $this->users = $condition->orderBy('ten', 'asc')
                ->get([ 'id', 'hodem', 'ten' ])
                ->toArray();
        }
    }

    public function view(): View
    {
        return view(name_blade('Backend.importer.export'), [
            'users' => $this->users
        ]);
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ( AfterSheet $event ) {
                $less      = 4;
                $max       = count($this->users) + $less;
                $cellRange = "E{$max}";
                $cellHead  = "E{$less}";

                /* Page type */
                $event->sheet->getDelegate()->getPageSetup()->setPaperSize(PageSetup::PAPERSIZE_A4);
                $event->sheet->getDelegate()->getPageSetup()->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

                /* Font size */
                $event->sheet->getDelegate()->getStyle("A1:{$cellRange}")->getFont()->setSize(12);

                /* Font family */
                $event->sheet->getDelegate()->getStyle("A1:{$cellRange}")->getFont()->setName('Times New Roman');

                /* Alignment Vertical */
                $event->sheet->getDelegate()->getStyle("A1:{$cellRange}")->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER);

                /* Alignment Horizontal */
                $event->sheet->getDelegate()->getStyle("A1:$cellHead")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle("A5:A{$max}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle("B5:{$cellRange}")->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_LEFT);

                /* Border */
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => [ 'argb' => '0000' ],
                        ],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle("A4:{$cellRange}")->applyFromArray($styleArray);
            },
        ];
    }
}
