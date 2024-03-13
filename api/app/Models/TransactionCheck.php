<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionCheck extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $table = 'transactions_checks';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'transaction_id',
        'check_id',
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
     * The relationship with transaction
     *
     * @return mixed
     */
    public function transaction() {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * The relationship with check
     *
     * @return mixed
     */
    public function check() {
        return $this->belongsTo(Check::class);
    }
}
