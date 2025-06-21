<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\QuestionnaireAction;
use App\Models\IdentificationDetails;

class QuestionnaireSession extends Model
{
    protected $fillable = [
        'uuid', 'final_node_key', 'started_at', 'completed_at',
    ];

    public function actions(): HasMany
    {
        return $this->hasMany(QuestionnaireAction::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function identificationDetail(): HasOne
    {
        return $this->hasOne(IdentificationDetails::class);
    }
}
