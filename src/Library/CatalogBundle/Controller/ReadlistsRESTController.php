<?php

namespace Library\CatalogBundle\Controller;

use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;
use Library\CatalogBundle\Entity\Readlists;
use Library\CatalogBundle\Form\ReadlistsType;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Library\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Voryx\RESTGeneratorBundle\Controller\VoryxController;

/**
 * Readlists controller.
 * @RouteResource("Readlists")
 */
class ReadlistsRESTController extends VoryxController
{
    /**
     * Get a Readlists entity
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     * @return Response
     *
     */
    public function getAction(Readlists $entity)
    {
        if ($entity->getUser()->getId() !==  $this->getUser()->getId()) {
            FOSView::create(null, 403);
        }
        return $entity;
    }
    /**
     * Get all Readlists entities.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     *
     * @QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
     * @QueryParam(name="limit", requirements="\d+", default="20", description="How many notes to return.")
     * @QueryParam(name="order_by", nullable=true, array=true, description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC")
     * @QueryParam(name="filters", nullable=true, array=true, description="Filter by fields. Must be an array ie. &filters[id]=3")
     */
    public function cgetAction(ParamFetcherInterface $paramFetcher)
    {
        try {
            $offset = $paramFetcher->get('offset');
            $limit = $paramFetcher->get('limit');
            $order_by = $paramFetcher->get('order_by');
            $filters = !is_null($paramFetcher->get('filters')) ? $paramFetcher->get('filters') : array();

            $em = $this->getDoctrine()->getManager();
            $filters['user'] = $this->getUser()->getId();
            $entities = $em->getRepository('CatalogBundle:Readlists')->findBy($filters, $order_by, $limit, $offset);
            if ($entities) {
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Create a Readlists entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     *
     * @param Request $request
     *
     * @return Response
     *
     */
    public function postAction(Request $request)
    {
        $entity = new Readlists();
        $form = $this->createForm(new ReadlistsType(), $entity, array("method" => $request->getMethod()));
        $this->removeExtraFields($request, $form);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setUser($this->getUser());
            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }
    /**
     * Update a Readlists entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putAction(Request $request, Readlists $entity)
    {
        $canEdit = $this->getUser()->hasRole(User::ROLE_SUPER_ADMIN)
            || $entity->getUser()->getId() == $this->getUser()->getId();
        if (!$canEdit) {
            return FOSView::create(null, 403);
        }
        try {
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            $form = $this->createForm(new ReadlistsType(), $entity, array("method" => $request->getMethod()));
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em->flush();

                return $entity;
            }

            return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Partial Update to a Readlists entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
*/
    public function patchAction(Request $request, Readlists $entity)
    {
        return $this->putAction($request, $entity);
    }
    /**
     * Delete a Readlists entity.
     *
     * @View(statusCode=204)
     * @Secure(roles="ROLE_READER")
     *
     * @param Request $request
     * @param $entity
     * @internal param $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, Readlists $entity)
    {
        $canEdit = $this->getUser()->hasRole(User::ROLE_SUPER_ADMIN)
            || $entity->getUser()->getId() == $this->getUser()->getId();
        if (!$canEdit) {
            return FOSView::create(null, 403);
        }
        try {
            if (in_array($entity->getType(), [ReadlistEnumType::IN_READ, ReadlistEnumType::READED, ReadlistEnumType::PAUSED])) {
                return FOSView::create('cannot delete this type', Codes::HTTP_BAD_REQUEST);
            }
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();

            return null;
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
