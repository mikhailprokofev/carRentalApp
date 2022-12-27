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
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
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
    public function store(StoreRentalRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $car = Rental::create($validated);
        return redirect()->route('rental.show', $car);
    }

    #[
        RA(
            summary: "Get Rental by ID",
            description: 'Display the specified Rental.',
    )]
    public function show(Rental $rental)
    {
        return new RentalResource($rental);
    }

    #[
        RA(
            summary: "Update Rental by ID",
            description: 'Update the specified Rental in storage.',
    )]
    public function update(UpdateRentalRequest $request, Rental $rental): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();
        $rental->fill($validated);
        $rental->save();
        return redirect()->route('rental.show', $rental);
    }

    #[
        RA(
            summary: "Delete Rental by ID",
            description: 'Remove the specified Rental from storage.',
    )]
    public function destroy(Rental $rental)
    {
        $rental->delete();
        return redirect()->route('rental.index');
    }
}
