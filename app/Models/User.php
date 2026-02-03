<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación con CustomerProfile
    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    /**
     * Cuestionarios asignados al usuario
     */
    public function questionnaires()
    {
        return $this->belongsToMany(Questionnaire::class, 'questionnaire_user')
            ->withPivot(['status', 'assigned_at', 'completed_at'])
            ->withTimestamps();
    }

    /**
     * Asignaciones de cuestionarios del usuario
     */
    public function questionnaireAssignments()
    {
        return $this->hasMany(QuestionnaireUser::class);
    }

    /**
     * Cuestionarios pendientes del usuario
     */
    public function pendingQuestionnaires()
    {
        return $this->questionnaires()->wherePivot('status', 'pending');
    }

    /**
     * Cuestionarios completados del usuario
     */
    public function completedQuestionnaires()
    {
        return $this->questionnaires()->wherePivot('status', 'completed');
    }

    /**
     * Citas custom asignadas al usuario
     */
    public function assignedAppointmentAvailabilities()
    {
        return $this->belongsToMany(AppointmentAvailability::class, 'appointment_availability_user', 'user_id', 'batch_id', 'id', 'batch_id')
            ->withTimestamps();
    }

    /**
     * Notificaciones del usuario
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    /**
     * Notificaciones no leídas del usuario
     */
    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * Contar notificaciones no leídas
     */
    public function getUnreadNotificationsCountAttribute(): int
    {
        return $this->unreadNotifications()->count();
    }
}
