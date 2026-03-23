<?php

namespace App\Controller;

use App\Repository\SoftwareVersionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class SoftwareDownloadController extends AbstractController
{
       private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

     #[Route('/carplay/software-download', name: 'software_download')]
    public function index(): Response
    {
        // The controller only renders the page with the form
        return $this->render('software_download/index.html.twig');
    }
}
