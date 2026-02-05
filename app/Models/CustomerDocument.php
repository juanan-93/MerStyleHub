<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CustomerDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_profile_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'document_type',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    /**
     * Relación con CustomerProfile
     */
    public function customerProfile()
    {
        return $this->belongsTo(CustomerProfile::class);
    }

    /**
     * Obtener la URL completa del archivo
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    /**
     * Obtener el tamaño del archivo formateado
     */
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        if (!$bytes) return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        
        return sprintf("%.2f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
    }

    /**
     * Eliminar el archivo físico del storage
     */
    public function deleteFile(): bool
    {
        if (Storage::exists($this->file_path)) {
            return Storage::delete($this->file_path);
        }
        return false;
    }

    /**
     * Boot del modelo para eliminar archivo al borrar registro
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($document) {
            $document->deleteFile();
        });
    }
}
