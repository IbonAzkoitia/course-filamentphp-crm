<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'image',
        'first_name',
        'last_name',
        'email',
        'phone',
        'birthday',
        'description',
        'job_title',
        'source_id',
        'url_linkedin',
        'url_website',
        'url_x',
        'street',
        'city',
        'state',
        'postcode',
        'country',
        'account_id',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }
}
