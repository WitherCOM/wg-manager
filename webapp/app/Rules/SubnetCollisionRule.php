<?php

namespace App\Rules;

use App\Helpers\IpAddress;
use App\Models\Subnet;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class SubnetCollisionRule implements ValidationRule, DataAwareRule
{
    protected $mask;

    protected $type;


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $fakeSubnet = new Subnet([
            'mask' => $this->mask,
            'network' => $this->network
        ]);
        foreach (Subnet::all() as $subnet)
        {
            if ($subnet->overlapsWith($fakeSubnet))
            {
                $fail(__('subnet_collision'));
                break;
            }
        }
    }

    public function setData(array $data)
    {
        $this->mask = $data['data']['mask'];
        $this->network = $data['data']['network'];
    }
}
