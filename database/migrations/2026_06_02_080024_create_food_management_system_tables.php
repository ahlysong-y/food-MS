<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Branch Management
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->timestamps();
        });

        // 2. Modify existing Users Table (បន្ថែម Column ចូលតារាងដើមរបស់ Laravel)
        Schema::table('users', function (Blueprint $table) {
            // បន្ថែម branch_id និង role បន្ទាប់ពី column 'id' និង 'name'
            $table->foreignId('branch_id')->nullable()->after('id')->constrained('branches')->onDelete('cascade');
            $table->string('role')->default('Staff')->after('name'); // e.g., Cook, Waiter, Cashier
        });

        // 3. Ingredient_Prep (Ingredients)
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('unit'); // e.g., kg, liter, pcs
            $table->timestamps();
        });

        // 3. Ingredient_Prep (Inventories per Branch)
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('ingredient_id')->constrained('ingredients')->onDelete('cascade');
            $table->decimal('quantity', 10, 2)->default(0);
            $table->timestamps();
        });

        // 4. Table_Setting & Guest_Seating
        Schema::create('dining_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->string('table_number');
            $table->string('status')->default('Table_Setting'); // e.g., Table_Setting, Guest_Seating, Occupied
            $table->timestamps();
        });

        // 5. Menu_Presentation
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // 6. Orders Master Table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('dining_table_id')->constrained('dining_tables')->onDelete('cascade');
            $table->foreignId('waiter_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('status')->default('Pending');
            $table->timestamps();
        });

        // 7. Food_Cooking, Quality_Checking, Food_Serving
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('status')->default('Food_Cooking'); // e.g., Food_Cooking, Quality_Checking, Food_Serving, Completed
            $table->timestamps();
        });

        // 8. Billing_Service
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
            $table->string('payment_method'); // e.g., Cash, Card, ABA_KHQR
            $table->string('status')->default('Billing_Service'); // e.g., Billing_Service, Paid
            $table->timestamps();
        });

        // 9. Dish_Washing & Kitchen_Sanitizing
        Schema::create('daily_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('task_type'); // e.g., Dish_Washing, Kitchen_Sanitizing
            $table->string('status')->default('Pending'); // e.g., Pending, In_Progress, Completed
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_tasks');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('dining_tables');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('ingredients');

        // លុបតែ Column វិញ មិនបាច់លុបតារាង users ទេ
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['branch_id', 'role']);
        });

        Schema::dropIfExists('branches');
    }
};
