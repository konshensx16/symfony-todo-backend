<?php

namespace App\Controller;

use App\Entity\TaskList;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreferenceController extends AbstractFOSRestController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getPreferencesAction(TaskList $list)
    {
        return $this->view($list->getPreferences(), Response::HTTP_OK);
    }

    /**
     * @Rest\RequestParam(name="sortValue", description="The value will be used to sort the list", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @param TaskList $list
     * @return \FOS\RestBundle\View\View
     */
    public function sortPreferencesAction(ParamFetcher $paramFetcher, TaskList $list)
    {
        $sortValue = $paramFetcher->get('sortValue');
        if ($sortValue) {
            $list->getPreferences()->setSortValue($sortValue);
            $this->entityManager->persist($list);
            $this->entityManager->flush();
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }

        $data['code'] = Response::HTTP_CONFLICT;
        $data['message'] = 'The sortValue cannot be null';

        return $this->view($data, Response::HTTP_CONFLICT);
    }

    /**
     * @Rest\RequestParam(name="filterValue", description="The filter value", nullable=false)
     * @param ParamFetcher $paramFetcher
     * @param TaskList $list
     * @return \FOS\RestBundle\View\View
     */
    public function filterPreferencesAction(ParamFetcher $paramFetcher, TaskList $list)
    {
        $filterValue = $paramFetcher->get('filterValue');
        if ($filterValue) {
            $list->getPreferences()->setFilterValue($filterValue);
            $this->entityManager->persist($list);
            $this->entityManager->flush();
            return $this->view(null, Response::HTTP_NO_CONTENT);
        }

        $data['code'] = Response::HTTP_CONFLICT;
        $data['message'] = 'The filterValue cannot be null';

        return $this->view($data, Response::HTTP_CONFLICT);
    }
}
