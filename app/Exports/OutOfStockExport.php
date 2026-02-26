<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OutOfStockExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(private Request $request) {}

    public function collection()
    {
        $q = $this->request->q;

        return Product::with('category')
            ->where('status', 1)
            ->where('stock_qty', '<=', 0)
            ->when($q, function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                });
            })
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return ['Product', 'Category', 'Barcode', 'Stock Qty'];
    }

    public function map($p): array
    {
        return [
            $p->name,
            $p->category?->name ?? '',
            $p->barcode,
            (int) $p->stock_qty,
        ];
    }
}