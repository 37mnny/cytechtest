<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // フィールド名をフォームで使用している名前に合わせる
    protected $fillable = [
        'product_name', 'price', 'stock', 'company_id', 'comment', 'img_path'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
