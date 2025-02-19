# Upgrade to 4.0 from 3.12

## High Impact Changes

### PHP 8.2 Required

`Laravel Cache` now required PHP 8.2.0 or greater.

### Laravel 11+ Required

`Laravel Cache` now required Laravel Framework 11 or greater.

### Composer Dependencies

You should update the following dependency in your application's composer.json file:

- `dragon-code/laravel-cache` to `^4.0`

The support of the following dependencies has been removed:

- `dragon-code/contracts`
- `dragon-code/simple-dto`

### Application Structure

The use of the dependence of the contracts `dragon-code/contracts` was removed.

This means that the `DragonCode\Contracts\*` contracts will no longer be working.

### Replaces Namespaces

- `DragonCode\Contracts\Support\Arrayable` replace with `Illuminate\Contracts\Support\Arrayable`
