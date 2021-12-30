<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Product;
use App\Notifications\PasswordReset;
use Rinvex\Subscriptions\Traits\HasSubscriptions;
use Gabievi\Promocodes\Traits\Rewardable;
use App\Models\BusinessDetail;
use Musonza\Chat\Traits\Messageable;
use App\Models\Badge;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use SoftDeletes;
    use HasSubscriptions;
    use Rewardable;
    use Messageable;

    protected $connection = 'mysql';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password', 'city_id', 'address', 'industry', 'job_freelance', 'start_as', 'is_active', 'designation'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'start_as' => 'array',
    ];

    /**
     * Checks if user has admin privilages
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function business()
    {
        return $this->hasOne(BusinessDetail::class, 'user_id', 'id');
    }

    public function businessContacts()
    {
        return $this->hasManyThrough(BusinessContactDetail::class, BusinessDetail::class, 'user_id', 'business_id');
    }

    public function businessCertificates()
    {
        return $this->hasManyThrough(BusinessCertification::class, BusinessDetail::class, 'user_id', 'business_id');
    }

    public function businessAwards()
    {
        return $this->hasManyThrough(BusinessAward::class, BusinessDetail::class, 'user_id', 'business_id');
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    /**
     * Get the e-mail address where password reset links are sent.
     * get's phone number if email is empty
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email ?: $this->phone;
    }

    public function subscriptionOrders()
    {
        return $this->hasMany(SubscriptionOrder::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function invoicesIssued()
    {
        return $this->hasMany(Invoice::class, 'seller_id', 'id');
    }

    public function invoicesReceived()
    {
        return $this->hasMany(Invoice::class, 'buyer_id')->where('is_shared', 1);
    }

    public function badges()
    {
      return $this->belongsToMany(Badge::class, 'user_badges');
    }

    public function businessDirector()
    {
        return $this->hasOne(BusinessDirector::class);
    }

    public function subscribedNotifications()
    {
        return $this->hasMany(NotificationSubscription::class);
    }

    public function companyBanner()
    {
        return $this->hasOne(CompanyPageBanner::class);
    }

    public function companyDisplayProducts()
    {
        return $this->hasMany(CompanyPageProduct::class);
    }

}
