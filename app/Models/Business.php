<?php

namespace App\Models;

use App\Enums\AccountRolesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Business extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'industry',
        'created_by',
        'status',
        'is_verified',
        'no_of_employees',
        'website_url'
    ];

    public function teamMembers(): HasMany
    {
        return $this->hasMany(User::class, 'business_id');
    }

    public function employees()
    {
        return $this->teamMembers()
            ->where('users.role_id', AccountRolesEnum::EMPLOYEE->value);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeBySearch($query, array $searchData)
    {
        $search = $searchData['search'] ?? '';

        return $query->where(function ($query) use ($search) {

            if (!empty($search)) $query->orWhere('businesses.name', 'LIKE', '%' . $search . '%')->orWhere('users.email', 'LIKE', '%' . $search . '%');

        })->orderBy('businesses.created_at', 'desc');
    }

}
