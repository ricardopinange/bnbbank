<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'description',
        'amount',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if (auth()->user()->type === 'Customer') {
                $model->user_id = auth()->user()->id;
            }
        });

        if (auth()->user()->type === 'Customer') {
            static::addGlobalScope(new UserScope);
        }
    }

    /**
     * The relationship with user
     *
     * @return mixed
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * The relationship with check
     *
     * @return mixed
     */
    public function check() {
        return $this->belongsToMany(Check::class, 'transactions_checks',
            'transaction_id', 'check_id');
    }
}
