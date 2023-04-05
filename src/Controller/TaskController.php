<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class TaskController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function index(): Response
    {
        $userRepo = $this->entityManager->getRepository(User::class);

        $users = $userRepo->findAll();

        foreach ($users as $user) {
            echo '<h1>' . $user->getName() . '</h1>';

            foreach ($user->getTasks() as $task) {
                echo '<h1>' .  $task->getTitle() . '</h1>';
            }
        }



        return $this->render('task/index.html.twig', [
            'controller_name' => 'TaskController',
        ]);
    }
}
