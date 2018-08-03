<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use GuzzleHttp\Client;


class VoucherCodeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function generate()
    {

        $input = Input::get();

        $validator = \Validator::make($input, [
            'expiry_date' => 'required|date',
            'special_offer' => 'required|integer|exists:mysql2.special_offers,id|
             unique:mysql2.voucher_codes,offer_id',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()], 400);
        } else {

            // TODO: We can make multiple calls and get user according to pagination for optimization
            $users = [];
            try {
                $client = new Client();
                $res = $client->get(url('/user/all/'));
                $users = json_decode($res->getBody(), true)['data'];

            } catch (\Exception $e) {
                return response()->json(['status' => 0, 'message' => $e->getMessage()], 400);
            }

            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    'code' => mt_rand(10000000, 99999999),
                    'offer_id' => $input['special_offer'],
                    'user_email' => $user['email'],
                    'expired_at' => new Carbon($input['expiry_date']),
                    'created_at' => Carbon::now()
                ];
            }

            if ($data)
                \App\VoucherCode::insert($data);

            return response()->json(['status' => 1, 'message' => 'Generated successfully.'], 200);
        }

    }

    public function use()
    {
        $input = Input::get();

        $validator = \Validator::make($input, [
            'email' => 'required|email',
            'voucher_code' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()], 400);
        } else {

            $user_exist = 0;
            try {
                $client = new Client();
                $res = $client->get(url('/user/emailExist?email='.$input['email']), ['form_params' => [],
                    'headers' => [
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ]]);
                $user_exist = json_decode($res->getBody(), true)['status'];
                $user_message = json_decode($res->getBody(), true)['message'];
            } catch (\Exception $e) {
                return response()->json(['status' => 0, 'message' => $e->getMessage()], 400);
            }

            if (!$user_exist) {

                return response()->json(['status' => 0, 'message' => $user_message], 400);
            }

            $voucher = \App\VoucherCode::where('user_email', $input['email'])->where('code', $input['voucher_code'])->first();
            if (!$voucher) {
                return response()->json(['status' => 0, 'message' => 'no voucher found against email.'], 200);
            } elseif (!is_null($voucher->toArray()['used_at'])) {
                return response()->json(['status' => 0, 'message' => 'voucher already used.'], 200);
            } elseif (strtotime($voucher->toArray()['expired_at']) <= strtotime(date('Y-m-d'))) {
                return response()->json(['status' => 0, 'message' => 'voucher expired against email.'], 200);
            } else {
                \App\VoucherCode::where('code', $input['voucher_code'])->update(['used_at' => Carbon::now()]);
            }


            $voucher = \App\VoucherCode::where('code', $input['voucher_code'])->with('offer')->first();

            return response()->json(['status' => 1, 'data' => ['Percentage discount' => $voucher->offer->discount], 'message' => 'Used successfully.'], 200);
        }

    }

    public function voucherByEmail()
    {

        $input = Input::get();

        $validator = \Validator::make($input, [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 0, 'message' => $validator->errors()], 400);
        } else {
            $voucher = \App\VoucherCode::where('user_email', $input['email'])
                ->whereNull('used_at')
                ->where('expired_at', '>', date('Y-m-d'))
                ->with('offer')->get()->toArray();
        }

        return response()->json(['status' => 1, 'data' => $voucher], 200);
    }
}
