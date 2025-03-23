<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class LoginAttempt extends Model
{
    protected $fillable = ['status','user_id','ip'];

    public function scopeAuthUser(Builder $query)
    {
        return $query->where('user_id',Auth::id());
    }

    public function scompeDateRange(Builder $query,$start,$end)
    {
        if($start && $end) return $query->whereBetween('created_at',[$start,$end]);
        if($start) return $query->where('created_at',$start);
        if($end) return $query->where('created_at',$end);
        return $query;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
