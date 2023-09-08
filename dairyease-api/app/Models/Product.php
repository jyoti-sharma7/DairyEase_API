<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'quantity', 'price', 'brand', 'description', 'product_photo'];
    public function getProductImageUrlAttribute(){
        if($this->product_photo){
            return asset('/uploads/product_images/'.$this-> product_photo);
        }
    }

    public $appends=[
        'product_image_url',
    ];
}
