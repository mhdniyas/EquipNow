<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'combo_price',
        'status',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'combo_price' => 'decimal:2',
    ];
    
    /**
     * Get the category that owns the combo.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * Get the items for the combo.
     */
    public function items()
    {
        return $this->hasMany(ComboItem::class);
    }
    
    /**
     * Get the bookings for the combo.
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_combos')
            ->withPivot('quantity', 'daily_rate')
            ->withTimestamps();
    }
}
