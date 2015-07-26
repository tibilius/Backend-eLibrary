<?php

namespace Library\CommentBundle\Controller;

use Library\CommentBundle\Entity\Comment;
use Library\CommentBundle\Form\CommentType;

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
 * Comment controller.
 * @RouteResource("Comment")
 */
class CommentRESTController extends VoryxController
{
    /**
     * Get a Comment entity
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc(
     *      resource=true,
     *      section="comments",
     *      statusCodes={
     *          200="Successful",
     *          404="Not Found"
     *      }
     * )
     *
     * @return Response
     *
     */
    public function getAction(Comment $entity)
    {
        return $entity;
    }
    /**
     * Get all Comment entities.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc(
     *      resource=true,
     *      section="comments",
     *      statusCodes={
     *          200="Successful",
     *          404="Not Found"
     *      }
     * )
     * @param ParamFetcherInterface $paramFetcher
     *
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
            $entities = $em->getRepository('LibraryCommentBundle:Comment')->findBy($filters, $order_by, $limit, $offset);
            if ($entities) {
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Get last Comment entities.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @ApiDoc(
     *      resource=true,
     *      section="comments",
     *      statusCodes={
     *          200="Successful",
     *          404="Not Found"
     *      }
     * )
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return Response
     *
     * @QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
     * @QueryParam(name="limit", requirements="\d+", default="20", description="How many notes to return.")
     * @QueryParam(name="commentator_id", requirements="\d+", nullable=true, description="User which commented")
     * @QueryParam(name="owner_id", requirements="\d+", nullable=true, description="User which answer we read")
     */
    public function cgetLastCommentAction(ParamFetcherInterface $paramFetcher){
        try {
            $offset = $paramFetcher->get('offset');
            $limit = $paramFetcher->get('limit');
            if (!$ownerId = (int)$paramFetcher->get('owner_id')) {
                $ownerId =  $this->getUser()->getId();

            }
            $commentatorId = $paramFetcher->get('commentator_id', null);
            $em = $this->getDoctrine()->getManager();
            $entities = $em->getRepository('LibraryCommentBundle:Comment')->findLast($ownerId, $commentatorId, $limit, $offset);
            if ($entities) {
                if ($ownerId === $this->getUser()->getId()) {
                    $this->getUser()->setTimeReadedComments();
                    $em->persist($this->getUser());
                    $em->flush();
                }
                return $entities;
            }
            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Create a Comment entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     * @ApiDoc(
     *      resource=true,
     *      section="comments",
     *      input="\Library\CommentBundle\Form\CommentType",
     *      statusCodes={
     *          200="Successful",
     *          400="Bad Request"
     *      }
     * )
     * @param Request $request
     * @Secure(roles="ROLE_READER")
     * @return Response
     *
     */
    public function postAction(Request $request)
    {
        $entity = new Comment();
        $form = $this->createForm(
            new CommentType('Library\CommentBundle\Entity\Comment'),
            $entity,
            array("method" => $request->getMethod())
        );
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
     * Update a Comment entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     * @ApiDoc(
     *      resource=true,
     *      section="comments",
     *      input="\Library\CommentBundle\Form\CommentType",
     *      statusCodes={
     *          200="Successful",
     *          400="Bad Request"
     *      }
     * )
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putAction(Request $request, Comment $entity)
    {
        try {
            if ($this->getUser()->getId() != $entity->getAuthor()->getId()) {
                return FOSView::create(null, 403);
            }
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            $form = $this->createForm(
                new CommentType('Library\CommentBundle\Entity\Comment'),
                $entity,
                array("method" => $request->getMethod())
            );
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
     * Partial Update to a Comment entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     * @ApiDoc(
     *      resource=true,
     *      section="comments",
     *      input="\Library\CommentBundle\Form\CommentType",
     *      statusCodes={
     *          200="Successful",
     *          400="Bad Request"
     *      }
     * )
     * @param Request $request
     * @param $entity
     *
     * @return Response
*/
    public function patchAction(Request $request, Comment $entity)
    {
        return $this->putAction($request, $entity);
    }
    /**
     * Delete a Comment entity.
     *
     * @View(statusCode=204)
     * @Secure(roles="ROLE_READER")
     * @param Request $request
     * @param $entity
     * @internal param $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, Comment $entity)
    {
        try {
            if ($this->getUser()->getId() != $entity->getAuthor()->getId()) {
                return FOSView::create(null, 403);
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
