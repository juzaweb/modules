<?php

namespace Juzaweb\CMS\Repositories\Events;

/**
 * Class RepositoryEntityUpdated
 *
 * @package Prettus\Repository\Events
 * @author Anderson Andrade <contato@andersonandra.de>
 */
class RepositoryEntityUpdated extends RepositoryEventBase
{
    /**
     * @var string
     */
    protected $action = "updated";
}
