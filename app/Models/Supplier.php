<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    public function products() {
        return $this->belongsToMany(Product::class);
    }

    public function approvedBy() {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
