<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuestionnaireSession extends Model
{
    protected $fillable = [
        'user_id', 'final_node_key', 'started_at', 'completed_at',
    ];

    public function actions(): HasMany
    {
        return $this->hasMany(QuestionnaireAction::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
