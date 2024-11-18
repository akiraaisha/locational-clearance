<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LocationalClearance extends Model
{

    /*        make fillable for
                $table->string('name');
                $table->string('lot_number')->nullable();
                $table->string('block_number')->nullable();
                $table->string('street_number')->nullable();
                $table->string('street_name')->nullable();
                //Foreign keys for subdivison, barangay, city_municipality,province
                $table->foreignId('subdivision_id')->nullable()->constrained();
                $table->foreignId('barangay_id')->nullable()->constrained();
                $table->foreignId('city_municipalities_id')->nullable()->constrained();
                $table->string('zip_code')->nullable();*/

    protected $fillable = [
        'name',
        'lot_number',
        'block_number',
        'street_number',
        'street_name',
        'subdivision_id',
        'barangay_id',
        'city_municipalities_id',
        'barangay_id',
        'city_municipalities_id',
        'zip_code'
    ];

    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

}
