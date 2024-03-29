<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com/cms
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Events\Users;

use Juzaweb\CMS\Models\User;

class RegisterCompleted
{
    public User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
