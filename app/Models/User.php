<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <--- សំខាន់បំផុតសម្រាប់ Login API

class User extends Authenticatable
{
    // ត្រូវប្រាកដថាមាន HasApiTokens នៅទីនេះ
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * កូឡោនដែលអាចវាយបញ្ចូលទិន្នន័យបាន
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'branch_id', // <--- កុំភ្លេចថែម branch_id ទីនេះ
    ];

    /**
     * លាក់ Password កុំអោយលោតចេញមកក្រៅពេលបាញ់ API
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * បង្កើតទំនាក់ទំនងរវាង User ទៅកាន់ Branch (១គណនី គ្រប់គ្រងដោយ១សាខា)
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
