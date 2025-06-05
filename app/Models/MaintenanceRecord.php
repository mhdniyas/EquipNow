<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRecord extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'equipment_id',
        'reported_by',
        'assigned_to',
        'issue',
        'status',
        'start_date',
        'end_date',
        'cost',
        'resolution_notes',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    
    /**
     * The maintenance statuses.
     */
    const STATUS_PENDING = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    
    /**
     * Get the equipment that owns the maintenance record.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
    
    /**
     * Get the user who reported the issue.
     */
    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
    
    /**
     * Get the user who is assigned to fix the issue.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
