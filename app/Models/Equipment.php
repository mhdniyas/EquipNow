<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
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
        'subcategory_id',
        'daily_rate',
        'deposit_amount',
        'status',
        'quantity',
        'quantity_available',
        'serial_number',
        'condition_notes',
    ];
    
    /**
     * The equipment statuses.
     */
    const STATUS_AVAILABLE = 'available';
    const STATUS_IN_USE = 'in_use';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_DAMAGED = 'damaged';
    
    /**
     * Get the category that owns the equipment.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * Get the subcategory that owns the equipment.
     */
    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }
    
    /**
     * Get the bookings for the equipment.
     */
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_equipment')
            ->withPivot('quantity', 'daily_rate', 'is_free')
            ->withTimestamps();
    }
    
    /**
     * Get the maintenance records for the equipment.
     */
    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }
    
    /**
     * Scope a query to only include available equipment.
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE)
            ->where('quantity_available', '>', 0);
    }
    
    /**
     * Scope a query to only include equipment in maintenance.
     */
    public function scopeInMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }
}
