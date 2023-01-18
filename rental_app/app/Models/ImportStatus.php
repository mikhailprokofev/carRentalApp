<?php

namespace App\Models;

use App\Module\Import\Enum\ImportStatusEnum;
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
     * Значения по умолчанию для атрибутов модели.
     *
     * @var array
     */
    protected $attributes = [
        'duplicated_rows' => 0,
        'validated_rows' => 0,
        'read_rows' => 0,
        'inserted_rows' => 0,
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

    public function updateStatusImport(ImportStatusEnum $status): void
    {
        $this->setAttribute('status', $status);
        $this->save();
    }

    public function addCountRowsImport(string $field, int $count): void
    {
        $count += $this->getAttributeValue($field);
        $this->setAttribute($field, $count);
        $this->save();
    }

    public static function initImport(string $fileName): self
    {
        return ImportStatus::create(['filename' => $fileName]);
    }
}
