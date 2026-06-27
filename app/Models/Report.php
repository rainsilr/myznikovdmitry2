<?php

namespace App\Models;

use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'category_id',
    'title',
    'description',
    'status',
    'date_incident',
    'contact',
    'feedback',
])]
class Report extends Model
{
    /** @use HasFactory<ReportFactory> */
    use HasFactory;

    /**
     * Пользователь, который отправил заявление.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Категория городской проблемы.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
