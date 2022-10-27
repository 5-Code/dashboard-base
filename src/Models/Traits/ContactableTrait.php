<?php

namespace Habib\Dashboard\Models\Traits;

use Habib\Dashboard\Models\Contact;

trait ContactableTrait
{
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
}
