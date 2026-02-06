<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    protected $fillable = [
        'admin_id',
        'customer_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Admin de la conversación
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Cliente de la conversación
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Mensajes de la conversación
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Último mensaje de la conversación
     */
    public function lastMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    /**
     * Mensajes no leídos para un usuario específico
     */
    public function unreadMessagesFor(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Marcar como leídos todos los mensajes del otro usuario
     */
    public function markAsReadFor(int $userId): void
    {
        $this->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Obtener o crear una conversación entre admin y customer
     */
    public static function getOrCreate(int $adminId, int $customerId): self
    {
        return self::firstOrCreate(
            ['admin_id' => $adminId, 'customer_id' => $customerId],
            ['last_message_at' => now()]
        );
    }

    /**
     * Scope: conversaciones de un usuario (admin o customer)
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('admin_id', $userId)
            ->orWhere('customer_id', $userId);
    }

    /**
     * Obtener el otro participante
     */
    public function getOtherParticipant(int $userId): User
    {
        return $this->admin_id === $userId ? $this->customer : $this->admin;
    }
}
