<?php

namespace App\Models;

// laravel passport token
use HashApiTokens;
use Laravel\Passport\HasApiTokens;
// laravel passport token
use App\Models\VoucherClaim;
use App\Models\Voucher;
use App\Models\CvSaya\CvSayaUserProfileDetail;
use App\Models\CvSaya\CvSayaCertifications;
// use App\Models\CvSaya\CvSayaUserProfileDetail;
// use App\Models\CvSaya\CvSayaUserProfileDetail;
// use App\Models\CvSaya\CvSayaUserProfileDetail;
// use App\Models\CvSaya\CvSayaUserProfileDetail;
use App\Models\BeautyTreatments\BeautyTreatmentCart;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasRoles;

    protected $primaryKey = 'id_kustomer';
    protected $table =  'kustomer';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'telpon', 'nama_lengkap', 'email', 'password', 'NIK', 'tgl_lahir','jam_slot', 'jeniskelamin', 'ID_perusahaan', 'alamat', 'diskon'
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

    public function userProfileDetail(){
        return $this->hasMany(CvSayaUserProfileDetail::class,'user_id','id_kustomer');
    }

    public function experiences(){
        return $this->hasMany(CvSayaUserProfileDetail::class,'user_id','id_kustomer');
    }

    public function certifications(){
        return $this->hasMany(CvSayaCertifications::class,'user_id','id_kustomer');
    }

    public function specialities(){
        return $this->hasMany(CvSayaUserProfileDetail::class,'user_id','id_kustomer');
    }

    public function social_medias(){
        return $this->hasMany(CvSayaUserProfileDetail::class,'user_id','id_kustomer');
    }

    public function hobbies(){
        return $this->hasMany(CvSayaUserProfileDetail::class,'user_id','id_kustomer');
    }
}
