<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnedItem extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'return_record_id',
        'equipment_id',
        'quantity',
        'condition',
        'damage_description',
        'damage_charges',
    ];
    
    /**
     * The equipment conditions.
     */
    const CONDITION_GOOD = 'good';
    const CONDITION_DAMAGED = 'damaged';
    const CONDITION_NEEDS_REPAIR = 'needs_repair';
    
    /**
     * Get the return record that owns the returned item.
     */
    public function returnRecord()
    {
        return $this->belongsTo(ReturnRecord::class);
    }
    
    /**
     * Get the equipment that owns the returned item.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
