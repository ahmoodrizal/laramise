<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $addresses = Address::whereBelongsTo(auth()->user())->get();
        // or
        $addresses = auth()->user()->addresses;

        return response()->json([
            'message' => 'Success fetch user addresses',
            'data' => $addresses
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'max:100'],
            'full_address' => ['required'],
            'phone' => ['required'],
            'prov_id' => ['required'],
            'city_id' => ['required'],
            'district_id' => ['required'],
            'postal_code' => ['required'],
            'is_default' => ['nullable']
        ]);
        $data['user_id'] = auth()->user()->id;

        $address = Address::create($data);

        return response([
            'message' => 'Success Add New Address',
            'data' => $address
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
        //
    }
}
