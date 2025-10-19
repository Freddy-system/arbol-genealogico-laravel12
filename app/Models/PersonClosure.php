<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonClosure extends Model
{
    public $timestamps = false;
    protected $table = 'person_closure';
    protected $fillable = ['ancestor_id', 'descendant_id', 'depth'];
}
