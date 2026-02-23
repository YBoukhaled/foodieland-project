<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'ingredients',
        'prep_time',
        'cook_time',   // Correction : 'cook_time' au lieu de 'cook-time'
        'image_url',
        'category_id', // Essentiel : pour lier la recette à une catégorie
        'category'     // Optionnel : si tu gardes aussi le nom en texte brut
    ];

    /**
     * Relation : Une recette appartient à une catégorie.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
