<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\VoucherCode;
use App\Offer;

class VoucherTest extends TestCase
{
    use DatabaseMigrations;

    public function testGenerateVouchers()
    {
        factory(User::class, 5)->create();
        $voucherCodes = factory(Offer::class, 1)->create();
        foreach( $voucherCodes as $code )
        {
            $data_code[] = [
                'id' => (int) $code->id,
                'name' => (string) $code->name,
                'discount' =>(float) $code->discount,
                'created_at' => (string) $code->created_at->toDateTimeString(),
                'updated_at' => (string) $code->updated_at->toDateTimeString()
            ];
        }

        $response = $this->json('POST', '/voucher_code/generate', [
            'special_offer' => $data_code[0]['id'],
            'expiry_date' => '12/09/2018'
        ],  [])
            ->seeJson([
                'status' => 1
            ]);
    }

    public function testGenerateVouchersWithInvalidExpiryDate()
    {
        factory(User::class, 5)->create();
        $voucherCodes = factory(Offer::class, 1)->create();
        foreach( $voucherCodes as $code )
        {
            $data_code[] = [
                'id' => (int) $code->id,
                'name' => (string) $code->name,
                'discount' =>(float) $code->discount,
                'created_at' => (string) $code->created_at->toDateTimeString(),
                'updated_at' => (string) $code->updated_at->toDateTimeString()
            ];
        }

        $response = $this->json('POST', '/voucher_code/generate', [
            'special_offer' => '',
            'expiry_date' => '12/09/2018'
        ],  [])
            ->seeJson([
                'status' => 0
            ]);
    }

    public function testGenerateVouchersWithInvalidOffer()
    {
        factory(User::class, 5)->create();
        $voucherCodes = factory(Offer::class, 1)->create();
        foreach( $voucherCodes as $code )
        {
            $data_code[] = [
                'id' => (int) $code->id,
                'name' => (string) $code->name,
                'discount' =>(float) $code->discount,
                'created_at' => (string) $code->created_at->toDateTimeString(),
                'updated_at' => (string) $code->updated_at->toDateTimeString()
            ];
        }

        $response = $this->json('POST', '/voucher_code/generate', [
            'special_offer' => $data_code[0]['id'],
            'expiry_date' => ''
        ],  [])
            ->seeJson([
                'status' => 0
            ]);
    }

    public function testAgainGenerateVouchersForSameUser()
    {
        factory(User::class, 5)->create();
        $voucherCodes = factory(Offer::class, 1)->create();
        foreach( $voucherCodes as $code )
        {
            $data_code[] = [
                'id' => (int) $code->id,
                'name' => (string) $code->name,
                'discount' =>(float) $code->discount,
                'created_at' => (string) $code->created_at->toDateTimeString(),
                'updated_at' => (string) $code->updated_at->toDateTimeString()
            ];
        }

        $this->json('POST', '/voucher_code/generate', [
            'expiry_date' => '12/09/2018',
            'special_offer' => $data_code[0]['id']
        ],  []);

        $this->refreshApplication();

        $this->json('POST', '/voucher_code/generate', [
            'expiry_date' => '12/09/2018',
            'special_offer' => $data_code[0]['id']
        ],  [])
            ->seeJson([
                'status' => 0
            ]);
    }

    public function testUseVouchersCode()
    {
        $users = factory(User::class, 1)->create();
        foreach( $users as $user )
        {
            $data_user[] = [
                'email' =>(string) $user->email,
                'name' => (string) $user->name,
                'created_at' => (string) $user->created_at->toDateTimeString(),
                'updated_at' => (string) $user->updated_at->toDateTimeString()
            ];
        }
        $voucherCodes = factory(Offer::class, 1)->create();
        foreach( $voucherCodes as $code )
        {
            $data_code[] = [
                'id' => (int) $code->id,
                'name' => (string) $code->name,
                'discount' =>(float) $code->discount,
                'created_at' => (string) $code->created_at->toDateTimeString(),
                'updated_at' => (string) $code->updated_at->toDateTimeString()
            ];
        }

        $this->json('POST', '/voucher_code/generate', [
        'expiry_date' => '12/09/2018',
        'special_offer' => $data_code[0]['id']
    ],  []);

        $this->refreshApplication();

        $vc = VoucherCode::where('user_email',$data_user[0]['email'])->where('offer_id',$data_code[0]['id'])->first();

        $this->json('POST', '/voucher_code/use', [
            'email' => $data_user[0]['email'],
            'voucher_code' => $vc->code
        ],  [])        ->seeJson([
            'status' => 1
        ]);;


    }



    public function testUseInvalidVouchersCodeAgainstEmail()
    {
        $users = factory(User::class, 1)->create();
        foreach( $users as $user )
        {
            $data_user[] = [
                'email' =>(string) $user->email,
                'name' => (string) $user->name,
                'created_at' => (string) $user->created_at->toDateTimeString(),
                'updated_at' => (string) $user->updated_at->toDateTimeString()
            ];
        }
        $voucherCodes = factory(Offer::class, 1)->create();
        foreach( $voucherCodes as $code )
        {
            $data_code[] = [
                'id' => (int) $code->id,
                'name' => (string) $code->name,
                'discount' =>(float) $code->discount,
                'created_at' => (string) $code->created_at->toDateTimeString(),
                'updated_at' => (string) $code->updated_at->toDateTimeString()
            ];
        }

        $this->json('POST', '/voucher_code/generate', [
            'expiry_date' => '12/09/2018',
            'special_offer' => $data_code[0]['id']
        ],  []);

        $this->refreshApplication();

        $vc = VoucherCode::where('user_email',$data_user[0]['email'])->where('offer_id',$data_code[0]['id'])->first();

        $this->json('POST', '/voucher_code/use', [
            'email' => $data_user[0]['email'],
            'voucher_code' => ''
        ],  [])        ->seeJson([
            'status' => 0
        ]);;


    }

    public function testUseVouchersCodeExpired()
    {
        $users = factory(User::class, 1)->create();
        foreach( $users as $user )
        {
            $data_user[] = [
                'email' =>(string) $user->email,
                'name' => (string) $user->name,
                'created_at' => (string) $user->created_at->toDateTimeString(),
                'updated_at' => (string) $user->updated_at->toDateTimeString()
            ];
        }
        $voucherCodes = factory(Offer::class, 1)->create();
        foreach( $voucherCodes as $code )
        {
            $data_code[] = [
                'id' => (int) $code->id,
                'name' => (string) $code->name,
                'discount' =>(float) $code->discount,
                'created_at' => (string) $code->created_at->toDateTimeString(),
                'updated_at' => (string) $code->updated_at->toDateTimeString()
            ];
        }

        $this->json('POST', '/voucher_code/generate', [
            'expiry_date' => '07/17/2018',
            'special_offer' => $data_code[0]['id']
        ],  []);

        $this->refreshApplication();

        $vc = VoucherCode::where('user_email',$data_user[0]['email'])->where('offer_id',$data_code[0]['id'])->first();

        $this->json('POST', '/voucher_code/use', [
            'email' => $data_user[0]['email'],
            'voucher_code' => $vc->code
        ],  [])        ->seeJson([
            'status' => 0
        ]);;


    }


}
