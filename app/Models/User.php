<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
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
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    // Relationships
    public function farmerProfile()
    {
        return $this->hasOne(FarmerProfile::class, 'user_id');
    }

    public function buyerProfile()
    {
        return $this->hasOne(BuyerProfile::class, 'user_id');
    }

    public function plots()
    {
        return $this->hasMany(Plot::class, 'farmer_id');
    }

    public function harvests()
    {
        return $this->hasMany(Harvest::class, 'farmer_id');
    }

    public function harvestBids()
    {
        return $this->hasMany(HarvestBid::class, 'buyer_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'farmer_id');
    }

    public function byproducts()
    {
        return $this->hasMany(Byproduct::class, 'farmer_id');
    }

    public function productBids()
    {
        return $this->hasMany(ProductBid::class, 'buyer_id');
    }

    public function byproductBids()
    {
        return $this->hasMany(ByproductBid::class, 'buyer_id');
    }

    public function givenRatings()
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    public function receivedRatings()
    {
        return $this->hasMany(Rating::class, 'rated_id');
    }

    public function isFarmer(): bool
    {
        return $this->user_type === 'farmer';
    }

    public function isBuyer(): bool
    {
        return $this->user_type === 'buyer';
    }
}
