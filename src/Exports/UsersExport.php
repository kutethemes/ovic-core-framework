<?php

namespace Ovic\Framework;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class UsersExport implements FromView, WithEvents
{
    public $users = [];

    public function __construct( $donvi_id = '' )
    {
        $user      = Auth::user();
        $donvi     = Donvi::getDonvi(true);
        $condition = User::where('status', 1);
        if ( empty($donvi_id) ) {
            $condition = $condition->whereIn('donvi_id', array_keys($donvi));
        } else {
            $condition = $condition->where('donvi_id', $donvi_id);
        }
        $this->users = $condition->get([ 'id', 'name', ])->toArray();
    }

    public function view(): View
    {
        return view(name_blade('Backend.importer.excel-users'), [
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
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                /* Alignment Horizontal */
                $event->sheet->getDelegate()->getStyle("A1:$cellHead")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle("A5:A{$max}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->getDelegate()->getStyle("B5:{$cellRange}")->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

                /* Border */
                $styleArray = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color'       => [ 'argb' => '0000' ],
                        ],
                    ],
                ];
                $event->sheet->getDelegate()->getStyle("A4:{$cellRange}")->applyFromArray($styleArray);
            },
        ];
    }
}
