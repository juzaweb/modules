<?php

namespace Juzaweb\CMS\Exceptions;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(
            function (Throwable $e) {
                //
            }
        );
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Throwable  $e
     * @return Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($this->is404Exception($e)) {
            if ($request->is(config('juzaweb.admin_prefix').'/*')) {
                return response()->view('cms::404', ['message' => $e->getMessage()], 404);
            }

            if (view()->exists(theme_viewname('theme::404'))) {
                return response()->view(
                    theme_viewname('theme::404'),
                    ['message' => 'Page not found'],
                    404
                );
            }

            return response()->view(
                'cms::404',
                ['message' => 'Page not found'],
                404
            );
        }

        return parent::render($request, $e);
    }

    protected function is404Exception($exception): bool
    {
        return match (true) {
            $exception instanceof NotFoundHttpException, $exception instanceof ModelNotFoundException => true,
            default => false,
        };
    }
}
