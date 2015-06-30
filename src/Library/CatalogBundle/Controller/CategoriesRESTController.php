<?php

namespace Library\CatalogBundle\Controller;

use Library\CatalogBundle\Entity\Categories;
use Library\CatalogBundle\Form\CategoriesType;

use FOS\RestBundle\Controller\Annotations\Route;
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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Voryx\RESTGeneratorBundle\Controller\VoryxController;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Categories controller.
 * @RouteResource("Categories")
 */
class CategoriesRESTController extends VoryxController
{
    /**
     * Get a Categories entity
     * @Secure(roles="ROLE_GUEST")
     * @View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc(
     *      resource=true,
     *      section="categories",
     *      statusCodes={
     *          200="Successful",
     *          404="Not Found"
     *      }
     * )
     * @return Response
     *
     */
    public function getAction(Categories $entity)
    {
        return $entity;
    }

    /**
     * Get all Categories entities.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_GUEST")
     * @param ParamFetcherInterface $paramFetcher
     *
     * @ApiDoc(
     *      resource=true,
     *      section="categories",
     *      statusCodes={
     *          200="Successful",
     *          404="Not Found"
     *      }
     * )
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
            $entities = $em->getRepository('CatalogBundle:Categories')->findBy($filters, $order_by, $limit, $offset);
            if ($entities) {
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a Categories entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_EXPERT")
     * @param Request $request
     * @ApiDoc(
     *      resource=true,
     *      section="categories",
     *      input="\Library\CatalogBundle\Form\CategoriesType",
     *      statusCodes={
     *          201="Created",
     *          400="Errors",
     *      }
     * )
     * @return Response
     *
     */
    public function postAction(Request $request)
    {
        $entity = new Categories();
        $form = $this->createForm(new CategoriesType(), $entity, array("method" => $request->getMethod()));
        $this->removeExtraFields($request, $form);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Update a Categories entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_EXPERT")
     * @param Request $request
     * @param $entity
     * @ApiDoc(
     *      resource=true,
     *      section="categories",
     *      input="\Library\CatalogBundle\Form\CategoriesType",
     *      statusCodes={
     *          201="Created",
     *          400="Errors",
     *      }
     * )
     * @return Response
     */
    public function putAction(Request $request, Categories $entity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            $form = $this->createForm(new CategoriesType(), $entity, array("method" => $request->getMethod()));
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
     * Partial Update to a Categories entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_EXPERT")
     * @ApiDoc(
     *      resource=true,
     *      section="categories",
     *      input="\Library\CatalogBundle\Form\CategoriesType",
     *      statusCodes={
     *          201="Created",
     *          400="Errors",
     *      }
     * )
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function patchAction(Request $request, Categories $entity)
    {
        return $this->putAction($request, $entity);
    }

    /**
     * Delete a Categories entity.
     *
     * @View(statusCode=204)
     * @Secure(roles="ROLE_EXPERT")
     * @param Request $request
     * @param $entity
     * @internal param $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, Categories $entity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();

            return null;
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
