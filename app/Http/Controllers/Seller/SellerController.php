<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\CreateSellerRequest;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::paginate(10);

        return view('seller.index', compact('sellers'));
    }

    public function create()
    {
        return view('seller.create');
    }

    public function store(CreateSellerRequest $request)
    {

        Seller::create($request->validated());

        return redirect()->route('seller.index')->with('success', 'Продавец успешно создан');
    }

    public function show(Seller $seller)
    {
        return view('seller.show', compact('seller'));
    }

    public function edit(Seller $seller)
    {
        return view('seller.edit', compact('seller'));
    }

    public function update(Request $request, Seller $seller) {}

    public function destroy(Seller $seller)
    {
        $seller->delete();

        return redirect()->route('seller.index')->with('success', 'Продавец успешно удален');
    }
}
