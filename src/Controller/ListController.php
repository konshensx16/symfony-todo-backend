<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\Preference;
use App\Entity\TaskList;
use App\Repository\TaskListRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListController extends AbstractFOSRestController
{
    /**
     * @var TaskListRepository
     */
    private $taskListRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var TaskRepository
     */
    private $taskRepository;

    public function __construct(TaskListRepository $taskListRepository, TaskRepository $taskRepository, EntityManagerInterface $entityManager)
    {
        $this->taskListRepository = $taskListRepository;
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @return \FOS\RestBundle\View\View
     */
    public function getListsAction()
    {
        $data = $this->taskListRepository->findAll();

        return $this->view($data, Response::HTTP_OK);
    }

    public function getListAction(TaskList $list)
    {
        $data = $list;
        return $this->view($data, Response::HTTP_OK);
    }

    /**
     * @Rest\RequestParam(name="title", description="Title of the list", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @return \FOS\RestBundle\View\View
     */
    public function postListsAction(ParamFetcher $paramFetcher)
    {
        $title = $paramFetcher->get('title');
        if ($title) {
            $list = new TaskList();

            $preferences = new Preference();

            $preferences->setList($list);
            $list->setPreferences($preferences);

            $list->setTitle($title);

            $this->entityManager->persist($list);
            $this->entityManager->flush();

            return $this->view($list, Response::HTTP_CREATED);
        }

        return $this->view(['title' => 'This cannot be null'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\RequestParam(name="title", description="Title of the new task", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @param TaskList $list
     * @return \FOS\RestBundle\View\View
     */
    public function postListTaskAction(ParamFetcher $paramFetcher, TaskList $list)
    {
        if ($list) {
            $title = $paramFetcher->get('title');

            $task = new Task();
            $task->setTitle($title);

            $list->addTask($task);

            $this->entityManager->persist($task);
            $this->entityManager->flush();

            return $this->view($task, Response::HTTP_OK);
        }

        return $this->view(['message' => 'Something went wrong'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function getListsTasksAction(TaskList $list)
    {
        return $this->view($list->getTasks(), Response::HTTP_OK);
    }

    /**
     * @Rest\FileParam(name="image", description="The background of the list", nullable=false, image=true)
     * @param Request $request
     * @param ParamFetcher $paramFetcher
     * @param TaskList $list
     * @return \FOS\RestBundle\View\View
     */
    public function backgroundListsAction(Request $request, ParamFetcher $paramFetcher, TaskList $list)
    {
        $currentBackground = $list->getBackground();
        if (!is_null($currentBackground)) {
            $filesystem = new Filesystem();
            $filesystem->remove(
                $this->getUploadsDir() . $currentBackground
            );
        }
        /** @var UploadedFile $file */
        $file = ($paramFetcher->get('image'));

        if ($file) {
            $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
            $file->move(
                $this->getUploadsDir(),
                $filename
            );

            $list->setBackground($filename);
            $list->setBackgroundPath('/uploads/' . $filename);

            $this->entityManager->persist($list);
            $this->entityManager->flush();

            $data = $request->getUriForPath(
                $list->getBackgroundPath()
            );

            return $this->view($data, Response::HTTP_OK);
        }

        return $this->view(['message' => 'Something went wrong'], Response::HTTP_BAD_REQUEST);
    }

    public function deleteListAction(int $id)
    {
        $list = $this->taskListRepository->findOneBy(['id' => $id]);

        $this->entityManager->remove($list);
        $this->entityManager->flush();

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\RequestParam(name="title", description="THe new title for the list", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @param TaskList $list
     * @return \FOS\RestBundle\View\View
     */
    public function patchListTitleAction(ParamFetcher $paramFetcher, TaskList $list)
    {
        $errors = [];
        $title = $paramFetcher->get('title');

        if (trim($title) !== '') {
            if ($list) {
                $list->setTitle($title);

                $this->entityManager->persist($list);
                $this->entityManager->flush();

                return $this->view(null, Response::HTTP_NO_CONTENT);
            }
            $errors[] = [
                'title' => 'This values cannot be empty'
            ];
        }
        $errors[] = [
            'list' => 'List not found'
        ];

        return $this->view($errors, Response::HTTP_NO_CONTENT);
    }

    private function getUploadsDir()
    {
        return $this->getParameter('uploads_dir');
    }
}
