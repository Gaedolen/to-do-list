<?php

namespace App\Controller;

use App\Form\TaskType;
use App\Entity\Task;
use App\Entity\Column;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/task/create/{column}', name: 'task_create', methods: ['POST'])]
    public function create(
        Column $column,
        Request $request,
        EntityManagerInterface $em
    ): Response {

        $task = new Task();
        $task->setColumn($column);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($task);
            $em->flush();

            return $this->json([
                'title' => $task->getTitle(),
                'important' => $task->isImportant()
            ]);
        }

        return $this->json(['error' => 'error'], 400);
    }
}
