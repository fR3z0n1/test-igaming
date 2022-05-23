<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentHistory;
use App\Models\PaymentReason;
use App\Models\PaymentTypeOperation;
use App\Models\WalletUserBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class WalletController extends Controller
{
    //For locking process
    protected const CACHE_TIMEOUT = 5;

    /**
     * Display balance
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): \Illuminate\Http\JsonResponse
    {
        //Validation parameters
        $wallet_id = $request->wallet_id;
        $user_id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'wallet_id' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    'code' => 422,
                    'message' => $validator->errors()->first(),
                ]
            ], 422);
        }

        //Check if the user has available wallets
        $wallets = WalletUserBalance::with(['wallet', 'currency'])->where('user_id', $user_id)
            ->where('wallet_id', $wallet_id)
            ->get();

        if ($wallets->count() > 0) {

            //Get a collection of exchange rates and calculate the total balance
            $account_num = number_format($wallets[0]->wallet->account_number, 0, ',', '-');

            //Get total balance for user (in RUB)
            $balance = WalletUserBalance::getTotalUserBalance($user_id, $wallets);
            return response()->json([
                'data' => [
                    'code' => 200,
                    'account_number' => $account_num,
                    'base' => "RUB",
                    'total' => $balance,
                ]
            ]);
        }

        //If the user does not have wallets
        return response()->json([
            'data' => [
                'message' => "Error: not found account number",
                'code' => 422,
            ]
        ], 422);
    }

    /**
     * Change of balance
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Request $request): \Illuminate\Http\JsonResponse
    {
        // Lock acquired indefinitely and automatically released
        $lock = Cache::lock('processing', self::CACHE_TIMEOUT, $request->user()->api_token);

        try {
            $lock->block(self::CACHE_TIMEOUT);

            //Get reasons for use in different parts of code
            $reasons = PaymentReason::get();

            //Validation parameters
            $validator = Validator::make($request->all(), [
                'wallet_id' => ['required', 'numeric'],
                'type_operation' => ['required', 'string', 'in:debit,credit'],
                'change_amount' => ['required', 'numeric'],
                'change_currency' => ['required', 'string', 'in:USD,RUB'], //Это имеет значение, пока разрешено только USD,RUB. Можно добавить получение из базы, как это выполнено с change_reasons
                'change_reason' => ['required', Rule::in($reasons->pluck('name')->toArray())]
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'data' => [
                        'code' => 422,
                        'message' => $validator->errors(),
                    ]
                ], 422);
            }

            //Get wallet for this currency
            $request->wallet = WalletUserBalance::where('wallet_id', $request->wallet_id)
                ->whereHas('currency', function ($query) use ($request) {
                    $query->where('key', $request->change_currency);
                })->lockForUpdate()->first();

            DB::begintransaction();

            switch ($request->type_operation) {
                case 'debit':
                    $request->wallet->balance += (float)$request->change_amount;
                    $request->wallet->save();
                    break;
                case 'credit':
                    $request->wallet->balance -= (float)$request->change_amount;

                    if ($request->wallet->balance < 0) {
                        DB::rollback();
                        return response()->json([
                            'data' => [
                                'code' => 422,
                                'message' => "Error: negative balance, amount is big",
                                'balance' => $request->wallet->balance + (float)$request->change_amount,
                            ]
                        ], 500);
                    }
                    $request->wallet->save();
                    break;
            }

            PaymentHistory::insert([
                'wallet_id' => $request->wallet_id,
                'currency_id' => $request->wallet->currency->id,
                'type_operation_id' => PaymentTypeOperation::OPERATION_IDS[$request->type_operation],
                'reason_id' => $reasons->where('name', $request->change_reason)->first()->id, //Причины прошли валидацию по значениям. Поэтому можно сразу обратиться к ID в коллекции
                'amount' => $request->change_amount,
                'created_at' => now()
            ]);
            DB::commit();

            //General static method for get balance
            //Show results (total balance)
            $balance = WalletUserBalance::getTotalUserBalance($request->user()->id);

            return response()->json([
                'data' => [
                    'code' => 200,
                    'wallet_id' => $request->wallet->id,
                    'base' => "RUB",
                    'total' => $balance,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'data' => [
                    'code' => 500,
                    'message' => $e->getMessage() . " line: " . $e->getLine(),
                ]
            ], 500);
        } finally {
            DB::rollback();
            optional($lock)->release();
        }
    }
}
