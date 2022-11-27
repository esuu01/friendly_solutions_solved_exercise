<?php

namespace App\Modules\Importer\Models;

use App\Core\LogModel;

class Importer extends LogModel
{
    protected $table = 'importer_log';
    protected $primaryKey  = 'id';

    const CREATED_AT = "run_at";
    const UPDATED_AT = null;

    public $timestamps = true;

    protected $fillable = [
        'type', 'entries_processed', 'entries_created'
    ];

    // relationships

    // scopes

    // getters
}
