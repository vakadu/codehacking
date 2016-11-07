<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //

    protected $uploads = '/images/';

    protected $fillable = ['file'];

    public function getFileAttribute($photo){  //name must be getFile Attribute bcz name file is coming from db photos table

        return $this ->uploads .$photo;
    }
}
