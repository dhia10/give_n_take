<?php





namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'redirect_to_login')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }
}
