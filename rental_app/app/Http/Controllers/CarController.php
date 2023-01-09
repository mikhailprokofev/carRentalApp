<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Attributes\RouteAttribute as RA;
use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Http\Resources\CarResource;
use App\Models\Car;

class CarController extends Controller
{
    #[
        RA(
            summary: 'Get All Cars',
            description: 'Display a listing of the Cars.',
        )]
    public function index()
    {
        return CarResource::collection(
            Car::with('rentals')->orderBy('created_at')->paginate(20)
        );
    }

    #[
        RA(
            summary: 'Create new Cars',
            description: 'Create a new specified Cars.',
        )]
    public function store(StoreCarRequest $request)
    {
        $validated = $request->validated();
        $validated['base_salary'] *= 100;
        $car = Car::create($validated);

        return $this->show($car);
    }

    #[
        RA(
            summary: 'Get Car by ID',
            description: 'Display the specified Car.',
        )]
    public function show(Car $car): CarResource
    {
        return new CarResource($car->load('rentals'));
    }

    #[
        RA(
            summary: 'Update Car by ID',
            description: 'Update the specified Car in storage.',
        )]
    public function update(UpdateCarRequest $request, Car $car)
    {
        $validated = $request->validated();
        if (! empty($validated['base_salary'])) {
            $validated['base_salary'] *= 100;
        }
        $car->fill($validated);
        $car->save();

        return $this->show($car);
    }

    #[
        RA(
            summary: 'Delete Car by ID',
            description: 'Remove the specified Car from storage.',
        )]
    public function destroy(Car $car)
    {
        $car->delete();

        return json_encode(['id' => $car->id]);
    }
}
