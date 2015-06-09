<?php

namespace Library\CatalogBundle\Controller;

use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;
use Library\CatalogBundle\Entity\ReadlistsBooks;
use Library\CatalogBundle\Form\ReadlistsBooksType;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Voryx\RESTGeneratorBundle\Controller\VoryxController;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * ReadlistsBooks controller.
 * @RouteResource("ReadlistsBooks")
 */
class ReadlistsBooksRESTController extends VoryxController
{
    /**
     * @JMS\DiExtraBundle\Annotation\Inject("library_catalogbundle.repository.readlistsbookrepository")
    */
    protected $repository;

    /**
     * Get a ReadlistsBooks entity
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     *
     * @return Response
     *
     */
    public function getAction(ReadlistsBooks $entity)
    {
        if ($entity->getReadlist()->getUser()->getId() !== $this->getUser()->getId()) {
            return FOSView::create(null, 403);
        }
        return $entity;
    }
    /**
     * Get all ReadlistsBooks entities.
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
            if (!isset($filters['readlist'])){
                $filters['readlist'] = $this->getDoctrine()->getRepository('CatalogBundle:Readlists')
                    ->findBy(['user' => $this->getUser()->getId()]);
            }
            $entities = $this->repository->findBy($filters, $order_by, $limit, $offset);
            if ($entities) {
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Create a ReadlistsBooks entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     * @param Request $request
     *
     * @return Response
     *
     */
    public function postAction(Request $request)
    {
        $entity = new ReadlistsBooks();
        $form = $this->createForm(new ReadlistsBooksType(), $entity, array("method" => $request->getMethod()));
        $this->removeExtraFields($request, $form);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if($entity->getReadlist()->getUser()->getId() !== $this->getUser()->getId()) {
                return FOSView::create(array('errors' => ['bad readlist']), Codes::HTTP_INTERNAL_SERVER_ERROR);
            }
            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }
    /**
     * Update a ReadlistsBooks entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putAction(Request $request, ReadlistsBooks $entity)
    {
        try {
            if ($entity->getReadlist()->getUser()->getId() !== $this->getUser()->getId()) {
                return FOSView::create(array('errors' => ['bad readlist']), Codes::HTTP_INTERNAL_SERVER_ERROR);
            }
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            $form = $this->createForm(new ReadlistsBooksType(), $entity, array("method" => $request->getMethod()));
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            if ($form->isValid()) {
                if ($entity->getReadlist()->getType() === ReadlistEnumType::READED) {
                    $this->repository->clearReadlists($entity, $this->getUser());
                }
                $em->flush();
                return $entity;
            }

            return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Partial Update to a ReadlistsBooks entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     * @param Request $request
     * @param $entity
     *
     * @return Response
*/
    public function patchAction(Request $request, ReadlistsBooks $entity)
    {
        return $this->putAction($request, $entity);
    }
    /**
     * Delete a ReadlistsBooks entity.
     *
     * @View(statusCode=204)
     * @Secure(roles="ROLE_READER")
     * @param Request $request
     * @param $entity
     * @internal param $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, ReadlistsBooks $entity)
    {
        try {
            if($entity->getReadlist()->getUser()->getId() !== $this->getUser()->getId()) {
                return FOSView::create(array('errors' => ['bad readlist']), Codes::HTTP_INTERNAL_SERVER_ERROR);
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
