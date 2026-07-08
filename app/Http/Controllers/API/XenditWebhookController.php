<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class XenditWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Verifikasi token webhook Xendit (mencegah callback palsu)
        $expectedToken = config('services.xendit.webhook_token');
        if (!empty($expectedToken) && $request->header('x-callback-token') !== $expectedToken) {
            return response()->json(['message' => 'Invalid callback token'], 401);
        }

        // 2. Dapatkan data JSON dari Xendit
        $data = $request->all();

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
