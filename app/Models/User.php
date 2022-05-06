<?php

namespace App\Models;

// laravel passport token
use HashApiTokens;
use Laravel\Passport\HasApiTokens;
// laravel passport token
use App\Models\VoucherClaim;
use App\Models\Voucher;
use App\Models\UserProfileDetail;
use App\Models\Certifications;
// use App\Models\CvSaya\CvSayaUserProfileDetail;
// use App\Models\CvSaya\CvSayaUserProfileDetail;
// use App\Models\CvSaya\CvSayaUserProfileDetail;
// use App\Models\CvSaya\CvSayaUserProfileDetail;
use App\Models\BeautyTreatments\BeautyTreatmentCart;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    use HasApiTokens, Notifiable, HasRoles;

    use \OwenIt\Auditing\Auditable;

    protected $primaryKey = 'id_kustomer';
    protected $table =  'kustomer';
    public $timestamps = false;
    protected $connection = 'kada';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'telpon', 'nama_lengkap', 'email', 'password', 'NIK', 'tgl_lahir', 'jam_slot', 'jeniskelamin', 'ID_perusahaan', 'alamat', 'diskon'
    ];

    protected $auditInclude = [
        'title',
        'content',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [
        'password', 'remember_token', 'NIK'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tgl_lahir' => 'date',
    ];

    public function voucherClaim()
    {
        return $this->hasMany(VoucherClaim::class, 'id_kustomer', 'id_kustomer');
    }

    public function ordersPayment()
    {
        return $this->hasMany(OrdersPayment::class, 'idlogin', 'id_kustomer');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'user', 'id_kustomer');
    }

    public function beautyTreatmentCarts()
    {
        return $this->hasMany(BeautyTreatmentCart::class, 'user_id', 'id_kustomer');
    }

    public function accessTokens()
    {
        return $this->hasMany(OauthAccessToken::class);
    }

    public function getAuthIdentifier()
    {
        return $this->id_kustomer;
    }
}
