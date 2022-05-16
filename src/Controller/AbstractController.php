<?php

namespace App\Controller;

use FOS\RestBundle\Context\Context;
use App\Builder\BuilderInterface;
use App\Services\AbstractService;
use FOS\RestBundle\{Controller\AbstractFOSRestController, View\View};
use Symfony\Component\HttpFoundation\{Request, Response};

abstract class AbstractController extends AbstractFOSRestController
{
    /**
     * AbstractController constructor.
     *
     * @param AbstractService $service
     * @param BuilderInterface $builder
     */
    public function __construct(
        protected readonly AbstractService $service,
        protected readonly BuilderInterface $builder
    ) {
    }

    /**
     * @param array $groups
     * @return Response
     */
    public function getAllAction(array $groups = []): Response
    {
        return $this->handleView(new View(
            $this->service->findAll(),
            Response::HTTP_OK
        ), $groups);
    }

    /**
     * @param $id
     * @param array $groups
     * @return Response
     */
    public function getAction($id, array $groups = []): Response
    {
        $entity = $this->service->find($id);
        $statusCode = $entity ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;

        return $this->handleView(new View($entity, $statusCode), $groups);
    }

    /**
     * @param Request $request
     * @param array $groups
     * @return Response
     */
    public function createAction(Request $request, array $groups = []): Response
    {
        $parameters = array_merge($request->query->all(), $request->toArray());
        $persistedEntity = $this->service->create($parameters);

        return $this->handleView(new View($persistedEntity, Response::HTTP_CREATED), $groups);
    }

    /**
     * @param string $id
     * @param Request $request
     * @param array $groups
     * @return Response
     */
    public function updateAction(string $id, Request $request, array $groups = []): Response
    {
        $params = $request->request->all();
        $params['id'] = $id;
        $persistedEntity = $this->service->update($params);

        return $this->handleView(new View($persistedEntity, Response::HTTP_OK), $groups);
    }

    /**
     * @param View $view
     * @param array $groups
     * @return Response
     */
    protected function handleView(View $view, array $groups = []): Response
    {
        if (!empty($groups)) {
            $context = new Context();
            $context->setGroups($groups);
            $view->setContext($context);
        }

        return $this->getViewHandler()->handle($view);
    }
}
