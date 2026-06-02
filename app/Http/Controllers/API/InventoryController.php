<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventory;
use App\Models\Ingredient;

class InventoryController extends Controller
{
    // ១. មុខងារសម្រាប់ ទាញយកបញ្ជីស្តុកប្រចាំសាខា (View Stock)
    public function getInventory(Request $request)
    {
        $user = $request->user(); // ចាប់យកបុគ្គលិកដែលកំពុង Login

        // ទាញយកស្តុកគ្រឿងផ្សំទាំងអស់ដែលមានក្នុងសាខារបស់គាត់
        $inventories = Inventory::with('ingredient')
            ->where('branch_id', $user->branch_id)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $inventories
        ], 200);
    }

    // ២. មុខងារសម្រាប់ បញ្ចូលស្តុកថ្មី (Add Stock)
    public function addStock(Request $request)
    {
        // ត្រួតពិនិត្យទិន្នន័យ
        $request->validate([
            'ingredient_id' => 'required|exists:ingredients,id',
            'quantity' => 'required|numeric|min:0.1' // អាចបញ្ចូលជាគីឡូ (ឧ. 1.5 kg)
        ]);

        $user = $request->user();

        // ស្វែងរកស្តុកគ្រឿងផ្សំនោះក្នុងសាខា បើអត់ទាន់មានទេ គឺបង្កើតថ្មីមួយដោយដាក់ចំនួនស្មើ ០ សិន
        $inventory = Inventory::firstOrCreate(
            ['branch_id' => $user->branch_id, 'ingredient_id' => $request->ingredient_id],
            ['quantity' => 0]
        );

        // បូកបន្ថែមចំនួនស្តុកថ្មី
        $inventory->quantity += $request->quantity;
        $inventory->save();

        return response()->json([
            'status' => 'success',
            'message' => 'បញ្ចូលស្តុកទទួលបានជោគជ័យ!',
            'data' => $inventory
        ], 200);
    }
}
