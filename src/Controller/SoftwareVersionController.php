<?php

namespace App\Controller;

use App\Repository\SoftwareVersionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SoftwareVersionController extends AbstractController
{
     #[Route('/software-versions', name: 'software_versions')]
    public function index(SoftwareVersionRepository $repo): Response
    {
        $versions = $repo->findAll(); // Fetch all records
        return $this->json($versions); // Return JSON response
    }

#[Route('/software-version/{id}', name: 'software_version_show')]
public function show(SoftwareVersionRepository $repo, int $id): Response
{
    $version = $repo->find($id);
    if (!$version) {
        return $this->json(['error' => 'Not found'], 404);
    }
    return $this->json($version);
}
  #[Route('/api/carplay/software/version', name: 'api_software_version_match', methods: ['POST'])]
  public function matchFirmware(Request $request, SoftwareVersionRepository $repo): Response
{
    $data = json_decode($request->getContent(), true);

    $systemVersion = $data['system_version'] ?? null;
    $systemVersionAlt = $data['system_version_alt'] ?? null;

    // Validate input
    if (!$systemVersion && !$systemVersionAlt) {
        return $this->json(['error' => 'system_version or system_version_alt is required'], 400);
    }

    // Query the repository for a matching software version
    $qb = $repo->createQueryBuilder('sv');

    if ($systemVersion && $systemVersionAlt) {
        $qb->where('sv.systemVersion = :systemVersion OR sv.systemVersionAlt = :systemVersionAlt')
           ->setParameter('systemVersion', $systemVersion)
           ->setParameter('systemVersionAlt', $systemVersionAlt);
    } elseif ($systemVersion) {
        $qb->where('sv.systemVersion = :systemVersion')
           ->setParameter('systemVersion', $systemVersion);
    } else {
        $qb->where('sv.systemVersionAlt = :systemVersionAlt')
           ->setParameter('systemVersionAlt', $systemVersionAlt);
    }

    $version = $qb->setMaxResults(1)->getQuery()->getOneOrNullResult();

    if (!$version) {
        return $this->json(['error' => 'No matching firmware found'], 404);
    }

    // Return the correct download link(s)
    return $this->json([
        'name' => $version->getName(),
        'link' => $version->getLink(),
        'st_link' => $version->getStLink(),
        'gd_link' => $version->getGdLink(),
       'latest' => $version->isLatest(), // ✅ Use isLatest()
    ]);
}
    // public function matchFirmware(Request $request, SoftwareVersionRepository $repo): Response
    // {
    //     // Parse JSON request body
    //     $data = json_decode($request->getContent(), true);

    //     $hardware = $data['hardware_version'] ?? null;
    //     $systemVersion = $data['system_version'] ?? null;
    //     $systemVersionAlt = $data['system_version_alt'] ?? null;

    //     // Validate input
    //     if (!$hardware || !$systemVersion) {
    //         return $this->json(['error' => 'hardware_version and system_version are required'], 400);
    //     }

    //     // Query the repository for a matching software version
    //     $version = $repo->createQueryBuilder('sv')
    //         ->where('sv.systemVersion = :systemVersion OR sv.systemVersionAlt = :systemVersionAlt')
    //         ->setParameter('systemVersion', $systemVersion)
    //         ->setParameter('systemVersionAlt', $systemVersionAlt)
    //         ->setMaxResults(1)
    //         ->getQuery()
    //         ->getOneOrNullResult();

    //     if (!$version) {
    //         return $this->json(['error' => 'No matching firmware found'], 404);
    //     }

    //     // Return the correct download link(s)
    //     return $this->json([
    //         'name' => $version->getName(),
    //         'link' => $version->getLink(),
    //         'st_link' => $version->getStLink(),
    //         'gd_link' => $version->getGdLink(),
    //         'latest' => $version->getLatest(),
    //     ]);
    // }
}
