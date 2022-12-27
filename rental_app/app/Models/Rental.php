<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Car;

class Rental extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'start_salary',
        'rental_start',
        'rental_end',
    ];

    /**
     * Атрибуты, которые должны быть типизированы.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

    public function getKeyType ()
    {
        return 'string';
    }

    /**
     * Атрибуты, которые должны быть типизированы.
     *
     * @var array
     */
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
