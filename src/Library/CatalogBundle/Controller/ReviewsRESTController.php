<?php

namespace Library\CatalogBundle\Controller;

use FOS\CommentBundle\Form\CommentType;
use Library\CatalogBundle\Entity\Reviews;
use Library\CatalogBundle\Form\ReviewsType;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Library\CommentBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Voryx\RESTGeneratorBundle\Controller\VoryxController;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * Reviews controller.
 * @RouteResource("Reviews")нrb
 */
class ReviewsRESTController extends VoryxController
{
    /**
     * Get a Reviews entity
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     * @return Response
     *
     */
    public function getAction(Reviews $entity)
    {
        return $entity;
    }

    /**
     * Get all Reviews entities.
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
            $entities = $em->getRepository('CatalogBundle:Reviews')->findBy($filters, $order_by, $limit, $offset);
            if ($entities) {
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a Reviews entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_EXPERT")
     *
     * @param Request $request
     * @return Response
     *
     */
    public function postAction(Request $request)
    {
        $entity = new Reviews();
        $form = $this->createForm(
            new ReviewsType($this->container->getParameter('max_revision_symbols')),
            $entity, array("method" => $request->getMethod())
        );
        $this->removeExtraFields($request, $form);
        $form->handleRequest($request);


        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
//            if ($entity->getBooks() && !$em->getRepository('CatalogBundle:Readlists')->isReaded($this->getUser(), $entity->getBooks())) {
//                return FOSView::create(array('errors' => 'unreaded'), Codes::HTTP_INTERNAL_SERVER_ERROR);
//            }
            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Partial Update to a Reviews entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     * @param $entity
     * @Secure(roles="ROLE_EXPERT")
     * @return Response
     */
    public function patchAction(Request $request, Reviews $entity)
    {
        return $this->putAction($request, $entity);
    }

    /**
     * Update a Reviews entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_EXPERT")
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putAction(Request $request, Reviews $entity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            $form = $this->createForm(
                new ReviewsType($this->container->getParameter('max_revision_symbols')),
                $entity, array("method" => $request->getMethod())
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
     * Vote a Books entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function postCommentAction(Request $request, Reviews $entity) {
        try {
            if(! $thread = $entity->getThread()) {
                $threadManager = $this->container->get('fos_comment.manager.thread');
                $thread = $threadManager->createThread();
                $thread->setCommentable(true);
                $entity->setThread($thread);
                $threadManager->saveThread($thread);
            }

            if (!$thread->isCommentable()) {
                return FOSView::create(null, 403);
            }

            /**
             * @var $comment \Library\CommentBundle\Entity\Comment
             */
            $comment = $this->container->get('fos_comment.manager.comment')->createComment($thread);
            $form = $this->createForm(new CommentType(), $comment, array("method" => $request->getMethod()));
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $comment->setAuthor($this->getUser());
                return $entity;
            }
            return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Vote a Books entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putCommentAction(Request $request, Reviews $entity, Comment $comment) {
        $em = $this->getDoctrine()->getManager();
        if(! $thread = $entity->getThread()) {
            return FOSView::create(null, 404);
        }
        $form = $this->createForm(new CommentType(), $comment, array("method" => $request->getMethod()));
        $this->removeExtraFields($request, $form);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->persist($comment);
            $em->flush();
            return $entity;
        }
        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }


    /**
     * Delete a Reviews entity.
     *
     * @View(statusCode=204)
     *
     * @param Request $request
     * @param $entity
     * @internal param $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, Reviews $entity)
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
