<?php

declare(strict_types=1);

namespace DragonCode\Cache\Concerns;

use ArrayAccess;
use ArrayObject;
use BackedEnum;
use Carbon\Carbon;
use Closure;
use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Facades\Instances\Instance;
use DragonCode\Support\Facades\Instances\Reflection;
use DragonCode\Support\Helpers\Ables\Arrayable as ArrayableHelper;
use Illuminate\Contracts\Support\Arrayable as IlluminateArrayable;
use Illuminate\Foundation\Http\FormRequest;

trait Arrayable
{
    protected function arrayMap(array $values, callable $callback): array
    {
        return Arr::of($values)
            ->map(function (mixed $value) {
                if ($this->isArrayable($value)) {
                    return $this->toArray($value);
                }

                if (is_object($value)) {
                    return get_class($value);
                }

                return $value;
            })
            ->flatten()
            ->filter(static fn ($value) => ! empty($value) || is_numeric($value) || is_bool($value))
            ->map($callback)
            ->values()
            ->toArray();
    }

    protected function arrayFlattenKeysMap(array $values, callable $callback): array
    {
        return Arr::of($values)
            ->flattenKeys()
            ->filter(static fn ($value) => ! empty($value) || is_numeric($value) || is_bool($value))
            ->map(fn (mixed $value, mixed $key) => $callback($key . '=' . $value))
            ->toArray();
    }

    protected function flattenKeys(mixed $array, string $delimiter = '.', ?string $prefix = null): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $new_key = ! empty($prefix) ? $prefix . $delimiter . $key : $key;

            if (is_array($value)) {
                $values = $this->flattenKeys($value, $delimiter, $new_key);

                $result = array_merge($result, $values);

                continue;
            }

            $result[$new_key] = $value;
        }

        return $result;
    }

    protected function toArray($value): array
    {
        return Arr::of(Arr::wrap($value))
            ->map(fn ($value) => Instance::of($value, Carbon::class) ? $value->toIso8601String() : $value)
            ->map(fn ($value) => Instance::of($value, FormRequest::class) ? $value->validated() : $value)
            ->map(fn ($value) => Instance::of($value, BackedEnum::class) ? ($value->value ?? $value->name) : $value)
            ->map(fn ($value) => is_object($value) ? (Arr::resolve($value) ?: get_class($value)) : $value)
            ->resolve()
            ->toArray();
    }

    protected function isArrayable($value): bool
    {
        if (is_array($value)) {
            return true;
        }

        if (
            is_string($value)
            && method_exists($value, 'toArray')
            && ! Reflection::isStaticMethod($value, 'toArray')
        ) {
            return false;
        }

        if (
            Instance::of($value, [
                ArrayableHelper::class,
                IlluminateArrayable::class,
                ArrayObject::class,
                ArrayAccess::class,
            ])
        ) {
            return true;
        }

        return Instance::of($value, Closure::class) && method_exists($value, 'toArray');
    }
}
