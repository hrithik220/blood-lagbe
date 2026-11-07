<?php

namespace App\Policies;

use App\Models\BloodRequest;
use App\Models\User;

class BloodRequestPolicy
{
    public function view(User $user, BloodRequest $bloodRequest): bool
    {
        return $user->id === $bloodRequest->user_id || $user->isAdmin();
    }

    public function update(User $user, BloodRequest $bloodRequest): bool
    {
        return $user->id === $bloodRequest->user_id && !in_array($bloodRequest->status, ['fulfilled', 'cancelled']);
    }

    public function delete(User $user, BloodRequest $bloodRequest): bool
    {
        return $user->id === $bloodRequest->user_id || $user->isAdmin();
    }
}
