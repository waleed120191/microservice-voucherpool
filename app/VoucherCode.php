<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class VoucherCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $connection= 'mysql2';
    protected $table = 'voucher_codes';


    protected $fillable = [
        'code', 'offer_id','user_email', 'expired_at','used_at'
    ];

    public function offer()
    {
        return $this->belongsTo('App\Offer');
    }

}
