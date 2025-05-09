<?php

namespace App\Services;

use App\Models\BillingAddress;
use Illuminate\Support\Facades\Auth;

class BillingAddressService
{
    public function saveBillingAddress(array $data, $userId)
    {
        $data['user_id'] = $userId;

        
        return BillingAddress::updateOrCreate(
            ['user_id' => $userId],  
            $data  
        );
    }
}