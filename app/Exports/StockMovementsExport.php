<?php

namespace App\Exports;

use App\Models\StockMovement;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockMovementsExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Request $request) {}

    public function collection()
    {
        $q     = $this->request->q;
        $type  = $this->request->type;
        $start = $this->request->start;
        $end   = $this->request->end;

        return StockMovement::with(['product', 'createdBy'])
            ->when($q, function ($query) use ($q) {
                $query->whereHas('product', function ($p) use ($q) {
                    $p->where('name', 'like', "%{$q}%")
                      ->orWhere('barcode', 'like', "%{$q}%");
                });
            })
            ->when($type, fn($query) => $query->where('movement_type', $type))
            ->when($start, fn($query) => $query->whereDate('created_at', '>=', $start))
            ->when($end, fn($query) => $query->whereDate('created_at', '<=', $end))
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return ['Date', 'Product', 'Barcode', 'Type', 'Qty Change', 'Reference', 'Note', 'By'];
    }

    public function map($m): array
    {
        $ref = ($m->reference_type && $m->reference_id) ? ($m->reference_type . ' #' . $m->reference_id) : '';

        return [
            optional($m->created_at)->format('Y-m-d H:i'),
            $m->product?->name ?? '',
            $m->product?->barcode ?? '',
            $m->movement_type,
            (int) $m->qty_change,
            $ref,
            $m->note ?? '',
            $m->createdBy?->name ?? '',
        ];
    }
}