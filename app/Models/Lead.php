<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'image',
        'first_name',
        'last_name',
        'email',
        'phone',
        'description',
        'job_title',
        'lead_status_id',
        'source_id',
        'url_linkedin',
        'url_website',
        'url_x',
        'street',
        'city',
        'state',
        'postcode',
        'country',
        'account_name',
        'account_revenue',
        'account_size_id',
        'industry_id',
        'partner_id',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class);
    }

    public function leadStatus(): BelongsTo
    {
        return $this->belongsTo(LeadStatus::class);
    }

    public function accountSize(): BelongsTo
    {
        return $this->belongsTo(AccountSize::class);
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }
}
