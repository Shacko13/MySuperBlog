<?php

namespace App;

use Carbon\Carbon;

class Post extends Model
{
  	public function comments() {
		return $this->hasMany(Comment::class);
	  }

	public function user() {
        return $this->belongsTo(User::class);
      }

	public function addComment($body) {
		$this->comments()->create(compact('body'));
	  }

	public function scopeFilter($query, $filters) {
    if (isset($filters['month'])) {
        $query->whereMonth('created_at', Carbon::parse($filters['month'])->month);       
      }

    if (isset($filters['year'])) {
        $query->whereYear('created_at', $filters['year']);       
      }

    if (isset($filters['recent'])) {
        $query->where( 'created_at', '>', Carbon::now()->subDays(14));
      }
    }
        
    public static function archives() {
    	return static::selectRaw('year(created_at) as year, monthname(created_at) as month, count(*) as published')
            ->groupBy('year', 'month')
            ->orderByRaw('min(created_at) desc')
            ->get()
            ->toArray();
    }
}

