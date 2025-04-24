<?php





namespace App\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class MainController
{
    #[Route('/', name: 'redirect_to_login')]
    public function index(): RedirectResponse
    {
        return new RedirectResponse('/login');
        // Or use the route name if defined: return $this->redirectToRoute('app_login');
    }
}

