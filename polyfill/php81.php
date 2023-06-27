<?php

declare(strict_types=1);

if (! class_exists(UnitEnum::class)) {
    interface UnitEnum
    {
        public static function cases(): array;
    }
}

if (! class_exists(BackedEnum::class)) {
    interface BackedEnum extends UnitEnum
    {
        public static function from(int|string $value): static;

        public static function tryFrom(int|string $value): static;
    }
}
