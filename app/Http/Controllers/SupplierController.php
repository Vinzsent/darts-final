<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Services\SupplierService;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierService $supplierService
    ) {}

    public function index(Request $request)
    {
        $search = $request->get('search');
        $suppliers = $this->supplierService
            ->getFiltered($search)
            ->paginate(15);

        return view('suppliers.index', compact('suppliers', 'search'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();
        $data['date_created'] = now();

        Supplier::create($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier created successfully.');
    }

    public function show(int $id)
    {
        $supplier = Supplier::with('procurements')->findOrFail($id);
        $stats = $this->supplierService->getSupplierStats($id);

        return view('suppliers.show', compact('supplier', 'stats'));
    }

    public function edit(int $id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, int $id)
    {
        $supplier = Supplier::findOrFail($id);
        $data = $request->validated();
        $data['last_updated_by'] = Auth::id();
        $data['date_updated'] = now();

        $supplier->update($data);

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(int $id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}
