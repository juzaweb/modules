<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\Network\Models;

use Juzaweb\CMS\Models\User as BaseUser;

class User extends BaseUser
{
    protected $table = 'subsite_users';
}
