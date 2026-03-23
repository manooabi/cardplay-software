<?php

namespace App\Controller;

use App\Entity\SoftwareVersion;
use App\Form\SoftwareVersionType;
use App\Repository\SoftwareVersionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/admin/software-version')]
#[IsGranted('ROLE_ADMIN')]
final class AdminSoftwareVersionController extends AbstractController
{
    // LIST ALL SOFTWARE VERSIONS
    // #[Route(name: 'app_admin_software_version_index', methods: ['GET'])]
    // public function index(SoftwareVersionRepository $softwareVersionRepository): Response
    // {
    //     return $this->render('admin_software_version/index.html.twig', [
    //         'software_versions' => $softwareVersionRepository->findAll(),
    //     ]);
    // }
#[Route(name: 'app_admin_software_version_index', methods: ['GET'])]
public function index(
    SoftwareVersionRepository $softwareVersionRepository,
    PaginatorInterface $paginator,
    Request $request
): Response {
    $query = $softwareVersionRepository->createQueryBuilder('s')
        ->orderBy('s.id', 'DESC')
        ->getQuery();

    $pagination = $paginator->paginate(
        $query,
        $request->query->getInt('page', 1),
        15 // items per page
    );

    return $this->render('admin_software_version/index.html.twig', [
        'software_versions' => $pagination,
    ]);
}
    // CREATE NEW SOFTWARE VERSION
    #[Route('/new', name: 'app_admin_software_version_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $softwareVersion = new SoftwareVersion();
        $form = $this->createForm(SoftwareVersionType::class, $softwareVersion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($softwareVersion);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_software_version_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_software_version/new.html.twig', [
            'software_version' => $softwareVersion,
            'form' => $form,
        ]);
    }

    // SHOW SOFTWARE VERSION DETAILS
    #[Route('/{id}', name: 'app_admin_software_version_show', methods: ['GET'])]
    public function show(SoftwareVersion $softwareVersion): Response
    {
        return $this->render('admin_software_version/show.html.twig', [
            'software_version' => $softwareVersion,
        ]);
    }

    // EDIT SOFTWARE VERSION
    #[Route('/{id}/edit', name: 'app_admin_software_version_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SoftwareVersion $softwareVersion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SoftwareVersionType::class, $softwareVersion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_software_version_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin_software_version/edit.html.twig', [
            'software_version' => $softwareVersion,
            'form' => $form,
        ]);
    }

    // DELETE SOFTWARE VERSION
    #[Route('/{id}', name: 'app_admin_software_version_delete', methods: ['POST'])]
    public function delete(Request $request, SoftwareVersion $softwareVersion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$softwareVersion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($softwareVersion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_software_version_index', [], Response::HTTP_SEE_OTHER);
    }
}