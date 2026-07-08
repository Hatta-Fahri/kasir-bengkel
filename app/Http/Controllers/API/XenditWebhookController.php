<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Dapatkan data JSON dari Xendit
        $data = $request->all();

        // Xendit Webhook Verification Token (Opsional, untuk keamanan)
        // $xenditToken = $request->header('x-callback-token');
        // if ($xenditToken !== config('services.xendit.webhook_token')) { ... }

        $status = $data['status'] ?? null;
        $invoiceId = $data['id'] ?? null;

        if (!$status || !$invoiceId) {
            return response()->json(['message' => 'Invalid data'], 400);
        }

        // Cari transaksi berdasarkan invoice ID
        $transaction = Transaction::where('xendit_invoice_id', $invoiceId)->first();

        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // Update status berdasarkan callback
        if ($status === 'PAID' || $status === 'SETTLED') {
            $transaction->update([
                'status' => 'selesai',
                'uang_diterima' => $transaction->total_bayar, // Lunas
                'kembalian' => 0,
            ]);
        } elseif ($status === 'EXPIRED') {
            $transaction->update([
                'status' => 'batal',
                'catatan' => 'Dibatalkan sistem: Invoice Kadaluarsa',
            ]);
        }

        return response()->json(['message' => 'Webhook received successfully']);
    }
}
