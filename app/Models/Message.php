<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'attachment_path',
        'attachment_name',
        'attachment_type',
        'attachment_size',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'attachment_size' => 'integer',
    ];

    /**
     * Conversación a la que pertenece el mensaje
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Remitente del mensaje
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * ¿Tiene archivo adjunto?
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }

    /**
     * ¿Es una imagen?
     */
    public function isImage(): bool
    {
        if (!$this->hasAttachment()) return false;
        return str_starts_with($this->attachment_type ?? '', 'image/');
    }

    /**
     * URL del archivo adjunto
     */
    public function getAttachmentUrlAttribute(): ?string
    {
        if (!$this->hasAttachment()) return null;
        return Storage::disk('public')->url($this->attachment_path);
    }

    /**
     * Tamaño formateado del archivo
     */
    public function getFormattedSizeAttribute(): string
    {
        if (!$this->attachment_size) return '0 B';

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->attachment_size;
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 1) . ' ' . $units[$i];
    }

    /**
     * ¿Fue leído?
     */
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    /**
     * Eliminar el archivo adjunto al eliminar el mensaje
     */
    protected static function booted(): void
    {
        static::deleting(function (Message $message) {
            if ($message->hasAttachment()) {
                Storage::disk('public')->delete($message->attachment_path);
            }
        });
    }
}
