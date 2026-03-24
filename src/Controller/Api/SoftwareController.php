<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\SoftwareMatcher;

class SoftwareController extends AbstractController
{

    #[Route('/api/software/version', methods: ['POST'])]
    public function check(Request $request, SoftwareMatcher $matcher): JsonResponse
    {
        $version = $request->request->get('version');
        $hwVersion = $request->request->get('hwVersion');

        $result = $matcher->match($version, $hwVersion);

        return $this->json($result);
    }
}