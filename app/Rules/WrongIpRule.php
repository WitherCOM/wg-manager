<?php

namespace App\Rules;

use App\Helpers\IpAddress;
use App\Models\Subnet;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class WrongIpRule implements ValidationRule
{
    protected Subnet $subnet;

    public function __construct(Subnet $subnet)
    {
        $this->subnet = $subnet;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!IpAddress::fromString($value)->inSubnet($this->subnet) || $this->subnet->peers()->pluck('ip_address')->contains($value))
        {
            $fail(__('wrong_ip'));
        }
    }


}
