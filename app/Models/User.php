<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Form\FormResponse;
use App\Models\Merchant\Merchant;
use App\Models\Payment\PendingRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Passport\HasApiTokens;
use Laravel\Scout\Searchable;

/**
 * @property mixed password
 * @property mixed id
 * @property string email
 * @property mixed first_name
 * @property mixed last_name
 * @property mixed phone
 * @property mixed status
 * @property mixed created_at
 * @property mixed email_verified_at
 * @property mixed phone_verified_at
 * @property float|int|mixed|string $suspended_until
 * @property Collection $merchants
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'password',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function merchants(): HasMany
    {
        return $this->hasMany(Merchant::class, 'owner_id');
    }

    public function pendingRequests(): HasMany
    {
        return $this->hasMany(PendingRequest::class, 'owner_id');
    }

    public function formResponses(): HasMany
    {
        return $this->hasMany(FormResponse::class);
    }
}
