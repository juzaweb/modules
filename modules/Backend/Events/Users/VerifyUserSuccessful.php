<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Backend\Events\Users;

use Juzaweb\CMS\Models\User;

class VerifyUserSuccessful
{
    public function __construct(public User $user)
    {
    }
}
