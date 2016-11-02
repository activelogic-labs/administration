<?php
/**
 * Created by Dalton Gibbs
 * Date: 11/1/16
 * Time: 9:57 PM
 */

namespace Activelogiclabs\Administration\Policies;

use App\User;

class AgentPolicy
{
    public function before($user)
    {
        if ($user->isDeveloper()) {
            return true;
        }
    }

    public function view(User $user)
    {
        return true;
    }
}