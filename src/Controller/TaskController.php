<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;


use App\Form\TaskType;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index(): Response
    {
        $tasks_repo = $this->entityManager->getRepository(Task::class);
        $tasks = $tasks_repo->findBy([], ['id' => 'DESC']);

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
        ]);
    }

    public function detail(Task $task)
    {
        if (!$task) {
            return $this->redirectToRoute('tasks');
        }
        return $this->render('task/detail.html.twig', [
            'task' => $task
        ]);
    }

    public function create(Request $request, UserInterface $user)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //setando el id del usuario y la hora que se creó la tarea
            $task->setUser($user);
            $task->setCreatedAt(new \DateTime('now'));

            $entityManager = $this->entityManager;

            $entityManager->persist($task);
            $entityManager->flush();

            //Te envia a la misma página para resetear los campos del form
            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function myTasks(UserInterface $user)
    {
        //Obtiene todos las tareas del usuario logueado
        $tasks = $user->getTasks();


        return $this->render('task/mytasks.html.twig', [
            'tasks' => $tasks
        ]);
    }

    public function edit(Request $request, Task $task, UserInterface $user)
    {
        if (!$user || $user->getId() != $task->getUser()->getId()) {
            return $this->redirectToRoute('tasks');
        }

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->entityManager;

            $entityManager->persist($task);
            $entityManager->flush();

            //Te envia a la misma página para resetear los campos del form
            return $this->redirectToRoute('tasks');
        }

        return $this->render('task/create.html.twig', [
            'edit' => true,
            'form' => $form->createView()
        ]);
    }

    public function delete(Task $task, UserInterface $user)
    {
        if (!$user || $user->getId() != $task->getUser()->getId()) {
            return $this->redirectToRoute('tasks');
        }

        if (!$task) {
            return $this->redirectToRoute('tasks');
        }

        $entityManager = $this->entityManager;
        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('tasks');
    }
}
