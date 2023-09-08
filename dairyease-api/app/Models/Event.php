<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['event_photo', 'event_description'];
    public function getEventImageUrlAttribute(){
        if($this->event_photo){
            return asset('/uploads/event_images/'.$this-> event_photo);
        }
    }

    public $appends=[
        'event_image_url',
    ];
}
