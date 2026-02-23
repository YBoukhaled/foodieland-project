<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin Foodieland',
            'email' => 'admin@foodieland.com',
            'password' => Hash::make('password123'),
            'role' => 'admin'
        ]);

        $categoriesData = [
            ['name' => 'Breakfast', 'image_url' => 'images/categories/breakfast.png'],
            ['name' => 'Healthy',     'image_url' => 'images/categories/healthy.png'],
            ['name' => 'Meat',      'image_url' => 'images/categories/meat.png'],
            ['name' => 'Dessert',   'image_url' => 'images/categories/dessert.png'],
            ['name' => 'Lunch',     'image_url' => 'images/categories/lunch.png'],
            ['name' => 'Chocolate', 'image_url' => 'images/categories/chocolate.png'],
        ];

        $categoryModels = [];
        foreach ($categoriesData as $data) {
            $category = Category::create([
                'name' => $data['name'],
                'image_url' => $data['image_url']
            ]);

            $categoryModels[$data['name']] = $category;
        }


        $recipes = [
            [
                "title" => "Pancakes",
                "description" => "Fluffy pancakes served with syrup, perfect for breakfast.",
                "ingredients" => "Flour, Eggs, Milk, Sugar, Baking powder, Butter",
                "prep_time" => 15,
                "cook_time" => 15,
                "image_url" => "https://img-3.journaldesfemmes.fr/X0OTQK0WZewmENXV5B9t_uhBh8o=/750x500/27400ba5c3d24e96b0cdc34494ddb668/ccmcms-jdf/40030649.jpg",
                "category" => "Dessert"
            ],
            [
                "title" => "Chocolate Cake",
                "description" => "Rich and moist chocolate cake perfect for dessert.",
                "ingredients" => "Flour, Cocoa powder, Eggs, Sugar, Butter, Baking powder, Milk",
                "prep_time" => 45,
                "cook_time" => 40,
                "image_url" => "https://www.indianhealthyrecipes.com/wp-content/uploads/2024/06/classic-chocolate-cake-recipe.jpg",
                "category" => "Dessert"
            ],
            [
                "title" => "Caesar Salad",
                "description" => "Crispy romaine lettuce with Caesar dressing and croutons.",
                "ingredients" => "Romaine lettuce, Parmesan, Croutons, Caesar dressing, Chicken (optional)",
                "prep_time" => 15,
                "cook_time" => 10,
                "image_url" => "https://media.chefdehome.com/750/0/0/caesar/classic-caesar-salad.jpg",
                "category" => "Healthy"
            ],
            [
                "title" => "Spaghetti Carbonara",
                "description" => "Classic Italian pasta with eggs, cheese, pancetta, and black pepper.",
                "ingredients" => "Spaghetti, Eggs, Parmesan, Pancetta, Black pepper",
                "prep_time" => 25,
                "cook_time" => 20,
                "image_url" => "https://static01.nyt.com/images/2021/02/14/dining/carbonara-horizontal/carbonara-horizontal-videoSixteenByNineJumbo1600-v2.jpg",
                "category" => "Meat"
            ],
            [
                "title" => "Avocado Toast",
                "description" => "Simple and healthy avocado toast for breakfast.",
                "ingredients" => "Bread, Avocado, Lemon juice, Salt, Pepper, Olive oil",
                "prep_time" => 10,
                "cook_time" => 10,
                "image_url" => "https://www.allrecipes.com/thmb/8NccFzsaq0_OZPDKmf7Yee-aG78=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/AvocadoToastwithEggFranceC4x3-bb87e3bbf1944657b7db35f1383fabdb.jpg",
                "category" => "Healthy"
            ],
            [
                "title" => "Chicken Meatballs with Cream Cheese",
                "description" => "Juicy chicken meatballs filled with cream cheese, perfect as a snack or main dish.",
                "ingredients" => "Ground chicken, Cream cheese, Bread crumbs, Eggs, Garlic, Salt, Pepper, Olive oil",
                "prep_time" => 30,
                "cook_time" => 25,
                "image_url" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRhZ8ZKP1OcgFOMYBzcO691njvLVZtEkQIs_g&s",
                "category" => "Meat"
            ],
              [
                "title" => "Spicy Delicious Chicken Wings",
                "description" => "Crispy and juicy chicken wings coated in a bold and spicy sauce. Perfect for parties or as a flavorful appetizer.",
                "ingredients" => "Chicken wings, Hot sauce, Garlic powder, Paprika, Salt, Black pepper, Butter",
                "prep_time" => 35,
                "cook_time" => 30,
                "image_url" => "../src/assets/images/HomeImg.png",
                "category" => "Meat"
            ]
        ];

        foreach ($recipes as $recipeData) {
            Recipe::create([
                'title'       => $recipeData['title'],
                'description' => $recipeData['description'],
                'ingredients' => $recipeData['ingredients'],
                'prep_time'   => $recipeData['prep_time'],
                'cook_time'   => $recipeData['cook_time'],
                'image_url'   => $recipeData['image_url'],
                'category'    => $recipeData['category'],
                'category_id' => $categoryModels[$recipeData['category']]->id,
            ]);
        }
    }
}
