<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\DiningTable;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // មុខងារសម្រាប់ បង្កើតការកុម្ម៉ង់ថ្មី (Place New Order)
    public function placeOrder(Request $request)
    {
        // ១. ពិនិត្យមើលទិន្នន័យដែល Frontend បញ្ជូនមក
        $request->validate([
            'dining_table_id' => 'required|exists:dining_tables,id',
            'items' => 'required|array', // ត្រូវមានបញ្ជីមុខម្ហូប
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user(); // ចាប់យកបុគ្គលិកដែលកំពុង Login (អ្នករត់តុ)

        try {
            // ចាប់ផ្តើម Transaction (បើមាន Error វានឹងត្រលប់ថយក្រោយវិញទាំងអស់)
            DB::beginTransaction();

            $totalAmount = 0;

            // ២. បង្កើតវិក្កយបត្រមេ (Order Master)
            $order = Order::create([
                'branch_id' => $user->branch_id,
                'dining_table_id' => $request->dining_table_id,
                'waiter_id' => $user->id,
                'total_amount' => 0, // យើងនឹងបូកបញ្ចូលតម្លៃពិតប្រាកដនៅខាងក្រោម
                'status' => 'Pending'
            ]);

            // ៣. បញ្ចូលមុខម្ហូបនីមួយៗទៅក្នុងវិក្កយបត្រ (Order Items)
            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                $quantity = $item['quantity'];

                // បូកសរុបតម្លៃ
                $totalAmount += ($menu->price * $quantity);

                // បង្កើត Order Item
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menu->id,
                    'quantity' => $quantity,
                    'status' => 'Food_Cooking' // ម្ហូបត្រូវបញ្ជូនទៅផ្ទះបាយភ្លាមៗ
                ]);
            }

            // ៤. ធ្វើបច្ចុប្បន្នភាពតម្លៃសរុបក្នុងវិក្កយបត្រមេ
            $order->update(['total_amount' => $totalAmount]);

            // ៥. ដូរស្ថានភាពតុទៅជា មានភ្ញៀវ (Occupied)
            DiningTable::where('id', $request->dining_table_id)->update(['status' => 'Occupied']);

            // បញ្ជាក់ថាជោគជ័យ
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'ការកុម្ម៉ង់ទទួលបានជោគជ័យ! ម្ហូបកំពុងបញ្ជូនទៅផ្ទះបាយ។',
                'order_id' => $order->id,
                'total_amount' => $totalAmount
            ], 201);
        } catch (\Exception $e) {
            // បើមានបញ្ហាអ្វីមួយ លុបទិន្នន័យដែលទើបបញ្ចូលចោលវិញ ដើម្បីកុំអោយខូច Database
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'មានបញ្ហាក្នុងការកុម្ម៉ង់៖ ' . $e->getMessage()
            ], 500);
        }
    }

    // មុខងារសម្រាប់ ទាញយកការកុម្ម៉ង់ដែលកំពុងចម្អិន (សម្រាប់ចុងភៅមើល)
    public function getCookingOrders()
    {
        $orders = OrderItem::with(['menu', 'order.diningTable'])
            ->where('status', 'Food_Cooking')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $orders
        ], 200);
    }
}
