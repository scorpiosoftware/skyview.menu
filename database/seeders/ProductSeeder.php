<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Appetizers
            ['name' => 'Garlic Bread', 'description' => 'Toasted bread with garlic butter and herbs', 'price' => 5.99, 'category' => 'Appetizers', 'image' => 'https://images.unsplash.com/photo-1573140247632-f8fd74997d5c?w=500&q=80'],
            ['name' => 'Bruschetta', 'description' => 'Toasted bread topped with tomatoes, garlic, and basil', 'price' => 7.99, 'category' => 'Appetizers', 'image' => 'https://images.unsplash.com/photo-1572695157366-5e585ab2b69f?w=500&q=80'],
            
            // Main Courses
            ['name' => 'Grilled Salmon', 'description' => 'Fresh salmon fillet with lemon butter sauce', 'price' => 24.99, 'category' => 'Main Courses', 'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=500&q=80'],
            ['name' => 'Beef Tenderloin', 'description' => 'Premium cut with red wine reduction', 'price' => 29.99, 'category' => 'Main Courses', 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&q=80'],
            
            // Pasta
            ['name' => 'Fettuccine Alfredo', 'description' => 'Creamy parmesan sauce with fresh pasta', 'price' => 16.99, 'category' => 'Pasta', 'image' => 'https://images.unsplash.com/photo-1645112411345-9c06c0c5c0a0?w=500&q=80'],
            ['name' => 'Spaghetti Bolognese', 'description' => 'Classic Italian meat sauce', 'price' => 15.99, 'category' => 'Pasta', 'image' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=500&q=80'],
            
            // Pizza
            ['name' => 'Margherita', 'description' => 'Fresh tomatoes, mozzarella, and basil', 'price' => 12.99, 'category' => 'Pizza', 'image' => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=500&q=80'],
            ['name' => 'Pepperoni', 'description' => 'Classic pepperoni with extra cheese', 'price' => 14.99, 'category' => 'Pizza', 'image' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=500&q=80'],
            
            // Salads
            ['name' => 'Caesar Salad', 'description' => 'Romaine lettuce, croutons, parmesan', 'price' => 9.99, 'category' => 'Salads', 'image' => 'https://images.unsplash.com/photo-1550304943-4f24f54ddde9?w=500&q=80'],
            ['name' => 'Greek Salad', 'description' => 'Fresh vegetables with feta cheese', 'price' => 10.99, 'category' => 'Salads', 'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&q=80'],
            
            // Soups
            ['name' => 'Tomato Soup', 'description' => 'Creamy tomato soup with basil', 'price' => 6.99, 'category' => 'Soups', 'image' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=500&q=80'],
            ['name' => 'Chicken Noodle', 'description' => 'Homemade chicken noodle soup', 'price' => 7.99, 'category' => 'Soups', 'image' => 'https://images.unsplash.com/photo-1547592166-23ac45744acd?w=500&q=80'],
            
            // Desserts
            ['name' => 'Tiramisu', 'description' => 'Classic Italian coffee-flavored dessert', 'price' => 8.99, 'category' => 'Desserts', 'image' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?w=500&q=80'],
            ['name' => 'Chocolate Cake', 'description' => 'Rich chocolate layer cake', 'price' => 7.99, 'category' => 'Desserts', 'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=500&q=80'],
            
            // Beverages
            ['name' => 'Fresh Lemonade', 'description' => 'Homemade lemonade with mint', 'price' => 4.99, 'category' => 'Beverages', 'image' => 'https://images.unsplash.com/photo-1621263764928-df1444c5e859?w=500&q=80'],
            ['name' => 'Iced Tea', 'description' => 'Fresh brewed iced tea', 'price' => 3.99, 'category' => 'Beverages', 'image' => 'https://images.unsplash.com/photo-1556679343-cf6d17fd5808?w=500&q=80'],
            
            // Breakfast
            ['name' => 'Avocado Toast', 'description' => 'Smashed avocado on sourdough', 'price' => 9.99, 'category' => 'Breakfast', 'image' => 'https://images.unsplash.com/photo-1588137378633-dea1336ce1e2?w=500&q=80'],
            ['name' => 'Eggs Benedict', 'description' => 'Poached eggs with hollandaise sauce', 'price' => 12.99, 'category' => 'Breakfast', 'image' => 'https://images.unsplash.com/photo-1608039829572-78524f79c4c7?w=500&q=80'],
            
            // Sandwiches
            ['name' => 'Club Sandwich', 'description' => 'Triple-decker with chicken and bacon', 'price' => 11.99, 'category' => 'Sandwiches', 'image' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=500&q=80'],
            ['name' => 'BLT', 'description' => 'Bacon, lettuce, and tomato', 'price' => 9.99, 'category' => 'Sandwiches', 'image' => 'https://images.unsplash.com/photo-1528735602780-2552fd46c7af?w=500&q=80'],
            
            // Burgers
            ['name' => 'Classic Burger', 'description' => 'Angus beef with special sauce', 'price' => 13.99, 'category' => 'Burgers', 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&q=80'],
            ['name' => 'Cheeseburger', 'description' => 'Double cheese with caramelized onions', 'price' => 14.99, 'category' => 'Burgers', 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=500&q=80'],
            
            // Seafood
            ['name' => 'Shrimp Scampi', 'description' => 'Garlic butter shrimp with pasta', 'price' => 22.99, 'category' => 'Seafood', 'image' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=500&q=80'],
            ['name' => 'Fish & Chips', 'description' => 'Beer-battered cod with fries', 'price' => 16.99, 'category' => 'Seafood', 'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=500&q=80'],
            
            // Vegetarian
            ['name' => 'Vegetable Stir Fry', 'description' => 'Mixed vegetables in soy sauce', 'price' => 14.99, 'category' => 'Vegetarian', 'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=500&q=80'],
            ['name' => 'Mushroom Risotto', 'description' => 'Creamy arborio rice with mushrooms', 'price' => 15.99, 'category' => 'Vegetarian', 'image' => 'https://images.unsplash.com/photo-1476124369491-e7addf5db371?w=500&q=80'],
            
            // Sides
            ['name' => 'French Fries', 'description' => 'Crispy golden fries', 'price' => 4.99, 'category' => 'Sides', 'image' => 'https://images.unsplash.com/photo-1630384060421-cb20d0e0649d?w=500&q=80'],
            ['name' => 'Onion Rings', 'description' => 'Crispy battered onion rings', 'price' => 5.99, 'category' => 'Sides', 'image' => 'https://images.unsplash.com/photo-1581006852262-e4307cf6283a?w=500&q=80'],
            
            // Specials
            ['name' => 'Chef\'s Special Pasta', 'description' => 'Daily chef\'s creation', 'price' => 18.99, 'category' => 'Specials', 'image' => 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?w=500&q=80'],
            ['name' => 'Surf & Turf', 'description' => 'Steak and lobster combination', 'price' => 39.99, 'category' => 'Specials', 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=500&q=80']
        ];

        foreach ($products as $product) {
            $category = Category::where('name', $product['category'])->first();
            
            Product::create([
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'image' => $product['image'],
                'category_id' => $category->id
            ]);
        }
    }
} 