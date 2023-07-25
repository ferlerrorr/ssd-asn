<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

class inv_lot extends Model
{
    protected $table = 'inv_lot';

    protected $fillable = ['InvNo', 'ItemCode', 'LotNo', 'ExpiryMM', 'ExpiryDD', 'ExpiryYYYY', 'Qty', 'SupCode', 'tStamp', 'remarks'];

    // Define a mutator to handle the input
    public function ExpiryYYYY($value)
    {
        // Take only the 3rd and 4th characters
        $this->attributes['ExpiryYYYY'] = substr($value, 2, 2);
    }
}
