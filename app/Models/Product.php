<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'short_description',
        'description',
        'image_path',
        'badge',
        'specs',
        'whatsapp_number',
        'is_active',
        'is_featured',
        'is_service',
    ];

    protected $casts = [
        'specs' => 'array',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_service' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if (empty($product->slug) || $product->isDirty('name')) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getWhatsappNumberFormattedAttribute(): string
    {
        $number = $this->whatsapp_number ?? '593958857369';
        $clean = preg_replace('/[^0-9]/', '', $number);
        if (str_starts_with($clean, '0')) {
            $clean = '593' . substr($clean, 1);
        }
        return $clean;
    }

    public function getWhatsappUrlAttribute(): string
    {
        $phone = $this->whatsapp_number_formatted;
        $type = $this->is_service ? 'servicio de reparación' : 'producto';
        $message = "Hola R.R Y Asociados | Hidraulic, me interesa cotizar el {$type}: {$this->name}.";
        
        if (!empty($this->specs) && is_array($this->specs)) {
            $specsList = [];
            foreach ($this->specs as $key => $val) {
                if (!empty($key) && !empty($val)) {
                    $specsList[] = "{$key}: {$val}";
                }
            }
            if (!empty($specsList)) {
                $message .= " (" . implode(', ', $specsList) . ")";
            }
        }

        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return 'https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=800&q=80';
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return asset('storage/' . $this->image_path);
    }
}
