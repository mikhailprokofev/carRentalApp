<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportStatus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
//        'id',
        'status',
        'duplicated_rows',
        'validated_rows',
        'read_rows',
        'inserted_rows',
        'filename',
    ];

    /**
     * Атрибуты, которые должны быть типизированы.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
        // TODO: возможно добавить статус
    ];

    public function getKeyType()
    {
        return 'string';
    }
}
