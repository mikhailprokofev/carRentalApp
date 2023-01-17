<?php

declare(strict_types=1);

namespace App\Module\Import\Rule;

use App\Repository\CustomCarRepositoryInterface;
use DateTime;
use Illuminate\Contracts\Validation\Validator;
use Ramsey\Uuid\UuidInterface;

final class RentalDomainRules implements DomainRulesInterface
{
    public function __construct(
        private CustomCarRepositoryInterface $carRepository,
    ) {}

    public static function rules(): array
    {
        return [
            'rental_start' => 'bail|required|date|after_or_equal:yesterday|workday',
            'rental_end' => 'bail|required|date|after_or_equal:rental_start|workday',
            'car_id' => 'required|exists:cars,id',
            'start_salary' => 'bail|required|integer|min:100000|max:500000'
        ];
    }

    public function afterValidate(Validator $validator): Validator
    {
        $validator->after(function (Validator $validator) {
            $validated = $validator->validated();
            $startAt = $validated['rental_start'];
            $endAt = $validated['rental_end'];
            $carId = $validated['car_id'];

            $this->addIntervalValidation($validator, $startAt, $endAt);
            $this->addAccessCarValidation($validator, $startAt, $endAt, $carId);
        });

        return $validator;
    }

    private function addIntervalValidation(
        Validator $validator,
        string $startAt,
        string $endAt,
        int $interval = 30,
    ): void {
        if ($this->assertRentalInterval($startAt, $endAt, $interval)) {
            $validator->errors()->add(
                'interval',
                "The interval between the start and end dates must not be more than $interval days",
            );
        }
    }

    private function assertRentalInterval(string $startAt, string $endAt, int $interval): bool
    {
        return date_diff(new DateTime($endAt), new DateTime($startAt))->days > $interval;
    }

    // TODO: проверка на доступность автомобиля (сделать без обращения к бд - иначе проверяет пустую базу)
    private function addAccessCarValidation(
        Validator $validator,
        string $startAt,
        string $endAt,
        UuidInterface $carId,
    ) {
        $result = $this->carRepository->findAvailableCarById($carId, $startAt, $endAt);

        if (empty($result->map(fn ($car) => $car->id)->toArray())) {
            $validator->errors()->add(
                'affordable',
                'There are already rentals for the selected date range',
            );
        }
    }
}
