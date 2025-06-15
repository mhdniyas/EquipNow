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
        'delivery_fee',
        'setup_fee',
        'insurance_fee',
        'payment_status',
        'payment_method',
        'status',
        'notes',
        'delivery_required',
        'setup_required',
        'insurance_required',
        'delivery_address',
        'delivery_date',
        'delivery_time',
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
        'delivery_date' => 'date',
        'delivery_required' => 'boolean',
        'setup_required' => 'boolean',
        'insurance_required' => 'boolean',
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
    
    /**
     * Scope a query to only include overdue bookings.
     */
    public function scopeOverdue($query)
    {
        return $query->where('end_date', '<', now())
            ->where('status', self::STATUS_ACTIVE);
    }
    
    /**
     * Get the total amount including all fees.
     */
    public function getTotalAmountAttribute()
    {
        return $this->total_rent + $this->delivery_fee + $this->setup_fee + $this->insurance_fee;
    }
    
    /**
     * Get the total amount paid.
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount');
    }
    
    /**
     * Get the remaining balance.
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->total_amount - $this->total_paid;
    }
    
    /**
     * Check if the booking is overdue.
     */
    public function getIsOverdueAttribute()
    {
        return $this->status === self::STATUS_ACTIVE && $this->end_date < now();
    }
    
    /**
     * Get the booking duration in days.
     */
    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
    
    /**
     * Get the booking status with color coding for UI.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_CONFIRMED => 'info',
            self::STATUS_ACTIVE => 'success',
            self::STATUS_RETURNED => 'secondary',
            self::STATUS_CANCELLED => 'danger',
            default => 'light'
        };
    }
    
    /**
     * Get the payment status with color coding for UI.
     */
    public function getPaymentStatusColorAttribute()
    {
        return match($this->payment_status) {
            self::PAYMENT_PENDING => 'warning',
            self::PAYMENT_PARTIAL => 'info',
            self::PAYMENT_PAID => 'success',
            self::PAYMENT_REFUNDED => 'secondary',
            default => 'light'
        };
    }
}
