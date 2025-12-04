<?php
namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Tabulator server-side endpoint
    public function index(Request $request)
    {
        // Tabulator sends: page, size, sorters, filters, etc. We'll map to typical params
        $query = Stock::with('store');

        // Search param (optional)
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('item_code', 'like', "%{$s}%")
                    ->orWhere('item_name', 'like', "%{$s}%")
                    ->orWhere('stock_no', 'like', "%{$s}%")
                    ->orWhere('location', 'like', "%{$s}%")
                    ->orWhere('status', 'like', "%{$s}%");

            });
        }

        // aescending desending
        if ($request->filled('sortField') && $request->filled('sortDir')) {
            $query->orderBy($request->sortField, $request->sortDir);
        } else {
            $query->orderBy('stock_no', 'desc');
        }

        $page = max(1, (int) $request->input('page', 1));
        $size = (int) $request->input('size', 10);

        $result = $query->paginate($size, ['*'], 'page', $page);

        // Tabulator expects { data: [...], last_page: n, total: x } or simply data & last_page?
        // We'll return structure Tabulator server side mode can use:
        return response()->json([
            'data'         => $result->items(),
            'last_page'    => $result->lastPage(),
            'total'        => $result->total(),
            'current_page' => $result->currentPage(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($data)
    {
        $data     = collect($data);
        $lastNo   = Stock::max('stock_no') ?? 0;
        $stock_no = $lastNo + 1;

        $model = Stock::create([
            'stock_no'      => $stock_no,
            'item_code'     => $data->get('item_code'),
            'item_name'     => $data->get('item_name'),
            'quantity'      => (int) $data->get('quantity', 0),
            'location'      => $data->get('location'),
            'store_name'      => $data->get('store_name'),
            'in_stock_date' => $data->get('in_stock_date'),
            'status'        => $data->get('status', 'pending'),
        ]);
        return $model;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $payload = $request->all();

        // If bulk: expect payload.records = [ ... ]
        if (isset($payload['records']) && is_array($payload['records'])) {
            $created = [];
            foreach ($payload['records'] as $rec) {
                $created[] = $this->create($rec);
            }
            return response()->json(['created' => $created]);
        }

        // Single:
        $stock = $this->create($payload);
        return response()->json($stock, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $stock = Stock::find($id);
        if (! $stock) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $stock->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
