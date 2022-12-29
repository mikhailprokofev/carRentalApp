<?php

namespace App\Http\Controllers;

use App\Http\Attributes\RouteAttribute as RA;
use App\Http\Requests\StoreRentalRequest;
use App\Http\Requests\UpdateRentalRequest;
use App\Http\Resources\RentalResource;
use App\Models\Rental;

class RentalController extends Controller
{
    #[
        RA(
            summary: 'Get All Rentals',
            description: 'Display a listing of the Rentals.',
    )]
    public function index()
    {
        return RentalResource::collection(
            Rental::with('car')->orderBy('created_at')->paginate(20)
        );
    }

    #[
        RA(
            summary: 'Create new Rentals',
            description: 'Create a new specified Rentals.',
    )]
    public function store(StoreRentalRequest $request)
    {
        $validated = $request->validated();
        $validated['start_salary'] *= 100;
        $rental = Rental::create($validated);
        return $this->show($rental);
    }

    #[
        RA(
            summary: "Get Rental by ID",
            description: 'Display the specified Rental.',
    )]
    public function show(Rental $rental)
    {
        return new RentalResource($rental->load('car'));
    }

    #[
        RA(
            summary: "Update Rental by ID",
            description: 'Update the specified Rental in storage.',
    )]
    public function update(UpdateRentalRequest $request, Rental $rental)
    {
        $validated = $request->validated();
        if(!empty($validated['start_salary'])){
            $validated['start_salary'] *= 100;
        }
        $rental->fill($validated);
        $rental->save();
        return $this->show($rental);
    }

    #[
        RA(
            summary: "Delete Rental by ID",
            description: 'Remove the specified Rental from storage.',
    )]
    public function destroy(Rental $rental)
    {
        $rental->delete();
        return redirect()->route('rentals.index');
    }
}
