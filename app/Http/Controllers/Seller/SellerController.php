<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\CreateSellerRequest;
use App\Http\Requests\Seller\UpdateSellerRequest;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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

        $data = $request->validated();

        $data['api_key'] = Crypt::encryptString($data['api_key']);

        Seller::create($data);

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

    public function update(UpdateSellerRequest $request, Seller $seller) {
        $data = $request->validated();

        if (isset($data['api_key']) && empty($data['api_key'])) {
            unset($data['api_key']);
        } else {

            $data['api_key'] = Crypt::encryptString($data['api_key']);
        }

        $seller->update($data);

        return redirect()->route('seller.index')->with('success', 'Продавец успешно обновлен');
    }

    public function destroy(Seller $seller)
    {
        $seller->delete();

        return redirect()->route('seller.index')->with('success', 'Продавец успешно удален');
    }
}
