<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    protected $fillable = ['spouse_a_id', 'spouse_b_id', 'start_date', 'end_date', 'status'];
}
