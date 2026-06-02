<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Branch;
use App\Models\User;
use App\Models\Menu;
use App\Models\DiningTable;
use App\Models\Ingredient;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ១. បង្កើតសាខាថ្មីមួយ (Branch)
        $branch = Branch::create([
            'name' => 'សាខាទី១ (Phnom Penh)',
            'address' => 'ភ្នំពេញ',
            'contact_number' => '012 345 678'
        ]);

        // ២. បង្កើតគណនីបុគ្គលិក (Users)
        // គណនី Admin
        User::create([
            'branch_id' => $branch->id,
            'name' => 'Admin Master',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'), // លេខសម្ងាត់គឺ password123
            'role' => 'Admin'
        ]);

        // គណនីអ្នករត់តុ
        User::create([
            'branch_id' => $branch->id,
            'name' => 'Sok Waiter',
            'email' => 'sok@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'Waiter'
        ]);

        // ៣. បង្កើតមុខម្ហូប (Menus)
        Menu::create([
            'name' => 'បាយឆាសាច់គោ',
            'price' => 2.50,
            'description' => 'បាយឆាពិសេសជាមួយសាច់គោស្រស់'
        ]);
        Menu::create([
            'name' => 'ស៊ុបតុមយាំគ្រឿងសមុទ្រ',
            'price' => 5.50,
            'description' => 'ស៊ុបជូរហិររសជាតិដើមខ្មែរ'
        ]);

        // ៤. បង្កើតតុញ៉ាំអាហារ (Dining Tables)
        DiningTable::create(['branch_id' => $branch->id, 'table_number' => 'T-01', 'status' => 'Table_Setting']);
        DiningTable::create(['branch_id' => $branch->id, 'table_number' => 'T-02', 'status' => 'Table_Setting']);

        // ៥. បង្កើតគ្រឿងផ្សំ (Ingredients)
        Ingredient::create(['name' => 'សាច់គោ', 'unit' => 'Kg']);
        Ingredient::create(['name' => 'អង្ករ', 'unit' => 'Kg']);
        Ingredient::create(['name' => 'ប្រេងឆា', 'unit' => 'Liter']);
    }
}
