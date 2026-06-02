<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\DiningTable;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // មុខងារសម្រាប់ ទូទាត់ប្រាក់ (Process Payment)
    public function processPayment(Request $request)
    {
        // ១. ពិនិត្យមើលទិន្នន័យដែលបានបញ្ជូនមក
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount_paid' => 'required|numeric|min:0',
            'payment_method' => 'required|string' // ឧទាហរណ៍៖ Cash, Card, ABA
        ]);

        try {
            // ចាប់ផ្តើមដំណើរការ Transaction
            DB::beginTransaction();

            // ទាញយកវិក្កយបត្រ (ប្រើ lockForUpdate ដើម្បីការពារកុំអោយអ្នកផ្សេងចុចគិតលុយជាន់គ្នាក្នុងពេលតែមួយ)
            $order = Order::lockForUpdate()->find($request->order_id);

            // ពិនិត្យមើលថាតើវិក្កយបត្រនេះគិតលុយរួចហើយឬនៅ?
            if ($order->status === 'Completed' || $order->status === 'Paid') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'វិក្កយបត្រនេះត្រូវបានទូទាត់ប្រាក់រួចរាល់ហើយ!'
                ], 400);
            }

            // ពិនិត្យមើលថាតើប្រាក់ដែលអតិថិជនអោយ គ្រប់គ្រាន់ឬទេ?
            if ($request->amount_paid < $order->total_amount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'ទឹកប្រាក់ដែលបង់មិនគ្រប់គ្រាន់ទេ! វិក្កយបត្រសរុបគឺ: $' . $order->total_amount
                ], 400);
            }

            // ២. កត់ត្រាការបង់ប្រាក់ចូលក្នុងតារាង Payment
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount_paid' => $request->amount_paid,
                'payment_method' => $request->payment_method,
                'status' => 'Paid'
            ]);

            // ៣. ប្តូរស្ថានភាពការកុម្ម៉ង់ (Order) ទៅជា បញ្ចប់ (Completed)
            $order->update(['status' => 'Completed']);

            // ៤. ប្តូរស្ថានភាពតុ (Table) ទៅជា Table_Setting វិញ (ដើម្បីអោយបុគ្គលិកដឹងថាត្រូវទៅសម្អាតតុនេះ)
            DiningTable::where('id', $order->dining_table_id)->update(['status' => 'Table_Setting']);

            // បញ្ជាក់ថាដំណើរការជោគជ័យ
            DB::commit();

            // គណនាប្រាក់អាប់ជូនភ្ញៀវវិញ
            $changeAmount = $request->amount_paid - $order->total_amount;

            return response()->json([
                'status' => 'success',
                'message' => 'ការទូទាត់ប្រាក់ទទួលបានជោគជ័យ!',
                'change_amount' => $changeAmount,
                'receipt_data' => $payment
            ], 200);
        } catch (\Exception $e) {
            // បើមាន Error ណាមួយ ត្រលប់ថយក្រោយវិញ
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'មានបញ្ហាក្នុងការទូទាត់ប្រាក់៖ ' . $e->getMessage()
            ], 500);
        }
    }
}
