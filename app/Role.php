<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    /**
     * @var string $slug
     */
    public $slug;

    /**
     * @var string $description
     */
    public $description;

    protected $fillable = [
        'slug',
        'description'
    ];

}
