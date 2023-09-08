<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['category_photo', 'category_name'];

    public function getCategoryImageUrlAttribute(){
        if($this->category_photo){
            return asset('/uploads/category_images/'.$this-> category_photo);
        }
    }

    public $appends=[
        'category_image_url',
    ];
}
