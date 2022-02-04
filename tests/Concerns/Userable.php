<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Tests\Fixtures\Models\User;

trait Userable
{
    protected $user_id = 123;

    protected $user_name = 'John Doe';

    protected function auth(): void
    {
        Auth::setUser(
            $this->createUser()
        );
    }

    protected function createUser(): Authenticatable
    {
        return new User([
            'id'   => $this->user_id,
            'name' => $this->user_name,
        ]);
    }
}
