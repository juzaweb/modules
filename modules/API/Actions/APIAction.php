<?php
/**
 * JUZAWEB CMS - The Best CMS for Laravel Project
 *
 * @package    juzaweb/cms
 * @author     Juzaweb Team <admin@juzaweb.com>
 * @link       https://juzaweb.com
 * @license    GNU General Public License v2.0
 */

namespace Juzaweb\API\Actions;

use Juzaweb\API\Http\Controllers\Frontend\ApiTokenController;
use Juzaweb\API\Support\Documentation\AuthSwaggerDocumentation;
use Juzaweb\API\Support\Documentation\PostTypeAdminSwaggerDocumentation;
use Juzaweb\API\Support\Documentation\PostTypeSwaggerDocumentation;
use Juzaweb\API\Support\Swagger\SwaggerDocument;
use Juzaweb\CMS\Abstracts\Action;

class APIAction extends Action
{
    public function handle(): void
    {
        $this->addAction(Action::BACKEND_INIT, [$this, 'addAdminMenus']);
        $this->addAction(Action::FRONTEND_INIT, [$this, 'addFrontendAjaxs']);

        if (config('juzaweb.api.frontend.enable')) {
            $this->addAction(Action::API_DOCUMENT_INIT, [$this, 'addAPIDocumentation'], 1);
        }
    }

    public function addAPIDocumentation(): void
    {
        $document = SwaggerDocument::make('frontend');
        $document->setTitle('Frontend');
        $document->append(AuthSwaggerDocumentation::class);
        $document->append(PostTypeSwaggerDocumentation::class);
        $this->hookAction->registerAPIDocument($document);
    }

    public function addAdminDocumentation(): void
    {
        $apiAdmin = SwaggerDocument::make('admin');
        $apiAdmin->setTitle('Admin');
        $apiAdmin->setPrefix('admin');
        $apiAdmin->append(PostTypeAdminSwaggerDocumentation::class);
        $this->hookAction->registerAPIDocument($apiAdmin);
    }

    public function addAdminMenus(): void
    {
        $this->hookAction->registerAdminPage(
            'api.documentation',
            [
                'title' => trans('cms::app.api_documentation'),
                'menu' => [
                    'icon' => 'fa fa-book',
                    'position' => 95,
                ],
            ]
        );
    }

    public function addFrontendAjaxs(): void
    {
        $this->registerFrontendAjax(
            'api-token.generate',
            [
                'method' => 'POST',
                'auth' => true,
                'callback' => [ApiTokenController::class, 'generateApiToken'],
            ]
        );
    }
}
