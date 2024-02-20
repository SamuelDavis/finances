<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Arr;
use Illuminate\Translation\PotentiallyTranslatedString;

/**
 * @phpstan-template Type
 */
class KeyIn implements ValidationRule
{
    /** @var Type[] */
    private array $values;

    /**
     * @param  Type|Type[]  $values
     */
    public function __construct(mixed $values)
    {
        $this->values = Arr::wrap($values);
    }

    /**
     * Run the validation rule.
     *
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     */
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail,
    ): void {
        $value = Arr::wrap($value);
        $keys = array_keys($value);
        $diff = array_diff($keys, $this->values);
        if (! empty($diff)) {
            $fail(trans('validation.in', compact('attribute')));
        }
    }
}
