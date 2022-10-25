<?php

namespace Habib\Dashboard\Actions\Auth;

use Habib\Dashboard\Actions\ActionInterface;
use Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ChangePasswordAction implements ActionInterface
{
    /**
     * @param Authenticatable|Model $authenticatable
     */
    public function __construct(public Authenticatable $authenticatable)
    {
    }

    /**
     * @inheritDoc
     */
    public function handle(array $data)
    {
        if (!Hash::check($data['old_password'], $this->authenticatable->getAuthPassword())) {
            return false;
        }
        $password = $data['password'];
        if ($data['password_hashed'] ?? false) {
            $password = Hash::make($data['password']);
        }
        return $this->authenticatable->update(compact('password'));
    }
}
