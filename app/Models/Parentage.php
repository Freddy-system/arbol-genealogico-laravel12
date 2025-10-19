<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parentage extends Model
{
    public $timestamps = false;
    protected $fillable = ['parent_id', 'child_id', 'type'];
}
