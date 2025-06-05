<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboItem extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'combo_id',
        'equipment_id',
        'quantity',
        'is_free',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_free' => 'boolean',
    ];
    
    /**
     * Get the combo that owns the combo item.
     */
    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }
    
    /**
     * Get the equipment that owns the combo item.
     */
    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
