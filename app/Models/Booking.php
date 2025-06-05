<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'user_id',
        'booking_date',
        'start_date',
        'end_date',
        'total_rent',
        'deposit_amount',
        'payment_status',
        'payment_method',
        'status',
        'notes',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'booking_date' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    
    /**
     * The booking statuses.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_ACTIVE = 'active';
    const STATUS_RETURNED = 'returned';
    const STATUS_CANCELLED = 'cancelled';
    
    /**
     * The payment statuses.
     */
    const PAYMENT_PENDING = 'pending';
    const PAYMENT_PARTIAL = 'partial';
    const PAYMENT_PAID = 'paid';
    const PAYMENT_REFUNDED = 'refunded';
    
    /**
     * Get the customer that owns the booking.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    
    /**
     * Get the user that created the booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the equipment items for the booking.
     */
    public function equipment()
    {
        return $this->belongsToMany(Equipment::class, 'booking_equipment')
            ->withPivot('quantity', 'daily_rate', 'is_free')
            ->withTimestamps();
    }
    
    /**
     * Get the combos for the booking.
     */
    public function combos()
    {
        return $this->belongsToMany(Combo::class, 'booking_combos')
            ->withPivot('quantity', 'daily_rate')
            ->withTimestamps();
    }
    
    /**
     * Get the payments for the booking.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    
    /**
     * Get the return record for the booking.
     */
    public function returnRecord()
    {
        return $this->hasOne(ReturnRecord::class);
    }
    
    /**
     * Scope a query to only include active bookings.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_CONFIRMED, self::STATUS_ACTIVE]);
    }
    
    /**
     * Scope a query to only include today's bookings.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('start_date', now()->toDateString());
    }
    
    /**
     * Scope a query to only include bookings due for return today.
     */
    public function scopeDueToday($query)
    {
        return $query->whereDate('end_date', now()->toDateString())
            ->where('status', self::STATUS_ACTIVE);
    }
}
