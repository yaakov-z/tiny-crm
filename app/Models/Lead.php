<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'customer_id', 'estimated_revenue', 'status',
        'description', 'disqualification_reason', 'disqualification_description'
    ];

    public function customer()
    {
        return $this->belongsTo(Account::class, 'customer_id');
    }

    public function qualify(): Deal
    {
        // Create Deal
        $deal = Deal::create([
            'lead_id' => $this->id,
            'title' => $this->title,
            'customer_id' => $this->customer_id,
            'estimated_revenue' => $this->estimated_revenue
        ]);

        $this->status = 3;
        $this->date_qualified = now();
        $this->update();

        return $deal;
    }

    public function disqualify(?int $reason, ?string $description): void
    {
        $this->status = 4;
        $this->date_disqualified = now();
        $this->disqualification_reason = $reason;
        $this->disqualification_description = $description;
        $this->update();
    }
}
