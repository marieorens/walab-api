<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="Utilisateur",
 *     description="Modèle utilisateur standard",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="firstname", type="string", example="Jean"),
 *     @OA\Property(property="lastname", type="string", example="Dupont"),
 *     @OA\Property(property="email", type="string", format="email", example="jean@mail.com"),
 *     @OA\Property(property="phone", type="string", example="+22901020304"),
 *     @OA\Property(property="role_id", type="integer", example=3),
 *     @OA\Property(property="created_at", type="string", format="date-time")
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,  HasPushSubscriptions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'gender',
        'country',
        'city',
        'date_naissance',
        'adress',
        'phone',
        'url_profil',
        'role_id',
        'status',
        'email_verified_at',
        'isdelete',
        'password',
        'token_notify',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'token_notify' => '',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function agendas(): HasMany
    {
        return $this->hasMany(Agenda::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function laboratorie(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Laboratorie::class);
    }

    public function practitioner(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Practitioner::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function getOrCreateWallet(string $type = 'laboratoire'): Wallet
    {
        return Wallet::getOrCreateForUser($this->id, $type);
    }
}
