<?php

declare(strict_types=1);

namespace DragonCode\Cache\Support;

use Carbon\Carbon;
use DateTimeInterface;
use DragonCode\Cache\Concerns\Call;
use DragonCode\Cache\Concerns\Has;

class Ttl
{
    use Call;
    use Has;

    public function fromMinutes($minutes): int
    {
        if ($this->hasDateTime($minutes)) {
            return $this->fromDateTime($minutes);
        } elseif ($this->hasClosure($minutes)) {
            $minutes = $this->call($minutes);
        }

        return $this->correct($minutes) * 60;
    }

    public function fromSeconds($seconds): int
    {
        if ($this->hasDateTime($seconds)) {
            return $this->fromDateTime($seconds);
        } elseif ($this->hasClosure($seconds)) {
            $seconds = $this->call($seconds);
        }

        return $this->correct($seconds);
    }

    public function fromDateTime(DateTimeInterface $date_time): int
    {
        $seconds = Carbon::now()->diffInRealSeconds($date_time, false);

        return $this->correct($seconds);
    }

    protected function correct($value): int
    {
        return abs((int) $value);
    }
}
