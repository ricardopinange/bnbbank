<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Check extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $table = 'checks';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'picture',
        'amount',
        'situation',
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
            $model->user_id = auth()->user()->id;
        });

        if (auth()->user()->type === 'Customer') {
            static::addGlobalScope(new UserScope);
        }
    }

    /**
     * The user that belong to the check.
     *
     * @return mixed
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * The transaction that belong to the check.
     *
     * @return mixed
     */
    public function transaction() {
        return $this->belongsToMany(Transaction::class, 'transactions_checks',
            'check_id', 'transaction_id');
    }
}
