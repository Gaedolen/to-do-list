<?php

namespace App\Controller;

use App\Entity\Workspace;
use App\Entity\Column;
use App\Entity\Task;
use App\Form\WorkspaceType;
use App\Form\TaskType;
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

        $user = $this->getUser();

        if (!$user) {
            throw new \LogicException('User must be logged in to create a workspace.');
        }

        $workspace->setUser($user);

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

    #[Route('/workspace', name: 'workspace_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        $workspaces = $em->getRepository(Workspace::class)
            ->findBy(['user' => $user]);

        return $this->render('workspace/index.html.twig', [
            'workspaces' => $workspaces
        ]);
    }

    #[Route('/workspace/{id}', name: 'workspace_show')]
    public function show(Workspace $workspace): Response
    {
        $user = $this->getUser();

        if ($workspace->getUser() !== $user) {
            throw $this->createAccessDeniedException();
        }

        // FORM POUR LA MODAL
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        return $this->render('workspace/show.html.twig', [
            'workspace' => $workspace,
            'taskForm' => $form->createView()
        ]);
    }
}