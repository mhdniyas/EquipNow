<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnRecord extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'booking_id',
        'user_id',
        'return_date',
        'condition_notes',
        'refund_amount',
        'refund_status',
        'damages_description',
        'damage_charges',
        'late_return_charges',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'return_date' => 'datetime',
    ];
    
    /**
     * The refund statuses.
     */
    const REFUND_NONE = 'none';
    const REFUND_PARTIAL = 'partial';
    const REFUND_FULL = 'full';
    
    /**
     * Get the booking that owns the return record.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
    
    /**
     * Get the user that created the return record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the returned equipment items.
     */
    public function returnedItems()
    {
        return $this->hasMany(ReturnedItem::class);
    }
}
