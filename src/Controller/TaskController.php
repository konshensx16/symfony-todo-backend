<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractFOSRestController
{

    /**
     * @var TaskRepository
     */
    private $taskRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(TaskRepository $taskRepository, EntityManagerInterface $entityManager)
    {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
    }

    public function getTaskActions(Task $task)
    {
        return $this->view($task, Response::HTTP_OK);
    }

    public function getTaskNotesAction(Task $task)
    {
        if ($task) {
            return $this->view($task->getNotes(), Response::HTTP_OK);
        }

        return $this->view(['message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Task $task
     * @return \FOS\RestBundle\View\View
     */
    public function deleteTaskAction(Task $task)
    {

        if ($task) {
            $this->entityManager->remove($task);
            $this->entityManager->flush();

            return $this->view(null, Response::HTTP_NO_CONTENT);
        }

        return $this->view(['message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Task $task
     * @return \FOS\RestBundle\View\View
     */
    public function statusTaskAction(Task $task)
    {
        if ($task) {
            $task->setIsComplete(!$task->getIsComplete());
            // FIXME: this needs to update and not remove
            $this->entityManager->persist($task);
            $this->entityManager->flush();

            // FIXME: this needs to return something and not NO_CONTENT
            return $this->view($task->getIsComplete(), Response::HTTP_OK);
        }

        return $this->view(['message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @Rest\RequestParam(name="note", description="Note for the task", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @param Task $task
     * @return \FOS\RestBundle\View\View
     */
    public function postTaskNoteAction(ParamFetcher $paramFetcher, Task $task)
    {
        $noteString = $paramFetcher->get('note');
        if ($noteString) {
            if ($task) {
                $note = new Note();

                $note->setNote($noteString);
                $note->setTask($task);

                $task->addNote($note);

                $this->entityManager->persist($note);
                $this->entityManager->flush();

                return $this->view($note, Response::HTTP_OK);
            }
        }

        return $this->view(['message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
