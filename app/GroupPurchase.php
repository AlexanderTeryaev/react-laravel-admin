<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupPurchase extends Model
{
    protected $fillable = [
        'group_id',
        'shop_training_id',
        'category_id',
        'price',
    ];
    /**
     * Get the group associated with the question.
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function training()
    {
        return $this->belongsTo(ShopTraining::class, 'shop_training_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
