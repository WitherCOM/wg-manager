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

    protected Subnet|null $subnet;

    public function __construct(Subnet|null $subnet)
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
        $fakeSubnet = new Subnet([
            'mask' => $this->mask,
            'network' => $this->network
        ]);
        foreach (Subnet::query()->whereNot('id', $this->subnet?->id)->get() as $subnet)
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
