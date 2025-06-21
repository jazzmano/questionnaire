<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdentificationDetails extends Model
{
    public $fillable = [
        'questionnaire_session_id',
        'name',
        'email',
        'company',
        'system_name',
    ];
    //
    public function session(): BelongsTo
    {
        return $this->belongsTo(QuestionnaireSession::class, 'questionnaire_session_id');
    }
}
