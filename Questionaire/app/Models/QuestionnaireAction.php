<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuestionnaireAction extends Model
{
    protected $fillable = [
        'questionnaire_session_id', 'node_key', 'selected_option', 'justification',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireSession::class);
    }
}
