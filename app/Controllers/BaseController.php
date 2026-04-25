<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController for CariIn.
 *
 * Loads the 'app_helper' globally and exposes the auth session conveniently.
 */
class BaseController extends Controller
{
    /**
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    protected $helpers = ['url', 'form', 'app', 'hashid'];

    /**
     * @var array<string,mixed>|null
     */
    protected ?array $authUser = null;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Ensure session is started
        session();

        $this->authUser = auth_user();
    }

    /**
     * Render view with a default layout.
     *
     * @param array<string,mixed> $data
     */
    protected function view(string $view, array $data = [], string $layout = 'layouts/public'): string
    {
        $data['auth_user']     = $this->authUser;
        $data['current_view']  = $view;
        $data['page_content']  = view($view, $data);

        return view($layout, $data);
    }
}
