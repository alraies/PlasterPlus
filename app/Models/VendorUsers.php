<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class VendorUsers extends Authenticatable
{
    use Notifiable;


    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }


}
