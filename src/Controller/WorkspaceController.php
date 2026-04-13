<?php

namespace App\Controller;

use App\Entity\Workspace;
use App\Entity\Column;
use App\Form\WorkspaceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class WorkspaceController extends AbstractController
{
    #[Route('/workspace/create', name: 'workspace_create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $workspace = new Workspace();

        $form = $this->createForm(WorkspaceType::class, $workspace);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($workspace);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('workspace/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}