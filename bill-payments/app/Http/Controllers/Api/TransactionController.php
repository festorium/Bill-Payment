<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    // POST: Create a new transaction
    public function createTransaction(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'transaction_code' => 'required|string|unique:transactions',
            'amount' => 'required|numeric|min:0',
            'transaction_type' => 'required|string|in:credit,debit',
            'status' => 'required|string|in:pending,completed,failed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create the transaction
        $transaction = Transaction::create($validator->validated());

        return new TransactionResource($transaction);
    }

    // GET: Show a single transaction
    public function getTransaction($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);
        return new TransactionResource($transaction);
    }

    // PUT/PATCH: Update an existing transaction
    public function updateTransaction(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'transaction_code' => 'sometimes|string|unique:transactions,transaction_code,' . $id,
            'amount' => 'sometimes|numeric|min:0',
            'transaction_type' => 'sometimes|string|in:credit,debit',
            'status' => 'sometimes|string|in:pending,completed,failed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $transaction->update($validator->validated());

        return new TransactionResource($transaction);
    }

    // DELETE: Delete a transaction
    public function deleteTransaction($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();

        return response()->json([
            'status' => true,
            'message' => 'Transaction deleted successfully.'
        ]);
    }
}
