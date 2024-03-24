<?php

namespace App\Infrastructure\Controller;

use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: '_app.default.index')]
    #[Template('default/index.html.twig')]
    public function index(): array
    {
        return [];
    }
}
