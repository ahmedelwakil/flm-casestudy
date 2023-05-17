<?php

namespace App\Units;

use Illuminate\Support\Str;

class PromoCodeCreationUnit implements Unit
{
    protected $code;
    protected $type;
    protected $value;
    protected $expiryDate;
    protected $maxNoOfUsages;
    protected $maxNoOfUsagesPerUser;
    protected $allowedUsers;

    public function __construct(string $type, float $value, $expiryDate, int $maxNoOfUsages, int $maxNoOfUsagesPerUser, array $allowedUsers = null)
    {
        $this->code = 'FLM-' . Str::random(4);
        $this->type = $type;
        $this->value = $value;
        $this->expiryDate = $expiryDate;
        $this->maxNoOfUsages = $maxNoOfUsages;
        $this->maxNoOfUsagesPerUser = $maxNoOfUsagesPerUser;
        $this->allowedUsers = $allowedUsers;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'expiry_date' => $this->expiryDate,
            'max_no_of_usages' => $this->maxNoOfUsages,
            'max_no_of_usages_per_user' => $this->maxNoOfUsagesPerUser,
            'allowed_users' => json_encode($this->allowedUsers)
        ];
    }
}
