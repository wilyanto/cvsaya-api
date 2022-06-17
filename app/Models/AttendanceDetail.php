<?php

namespace App\Models;

use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\SpatialBuilder;

class AttendanceDetail extends Model
{
    use HasFactory, UuidGenerator;

    protected $fillable = [
        'attendance_type',
        'attended_at',
        'scheduled_at',
        'attendance_qr_code_id',
        'image',
        'location',
        'ip',
        'verified_by',
        'verified_at'
    ];

    protected $casts = [
        'location' => Point::class
    ];

    public function newEloquentBuilder($query): SpatialBuilder
    {
        return new SpatialBuilder($query);
    }

    public function attendancePenalty()
    {
        return $this->hasOne(AttendancePenalty::class, 'attendance_detail_id', 'id');
    }

    public function outsideRadiusAttendance()
    {
        return $this->hasOne(OutsideRadiusAttendance::class, 'attendance_detail_id', 'id');
    }

    public function getImageUrl()
    {
        if (!$this->image) {
            return null;
        }
        return url('/storage/images/attendances/' . $this->image);
    }
}
