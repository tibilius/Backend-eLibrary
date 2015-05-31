<?php

namespace Library\CatalogBundle\Controller;

use Library\CatalogBundle\DBAL\Types\ReadlistEnumType;
use Library\CatalogBundle\Entity\Books;
use Library\CatalogBundle\Form\BooksType;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Library\CommentBundle\Entity\Comment;
use Library\CommentBundle\Entity\Thread;
use FOS\CommentBundle\Form\CommentType;
use Library\VotesBundle\Entity\Rating;
use Library\VotesBundle\Entity\Vote;
use Library\VotesBundle\Form\VoteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Voryx\RESTGeneratorBundle\Controller\VoryxController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use JMS\DiExtraBundle\Annotation as DI;
/**
 * Books controller.
 * @RouteResource("Books")
 */
class BooksRESTController extends VoryxController
{
    /**
     * @DI\Inject("library_catalogbundle.repository.bookrepository")
     */
    protected $booksRepository;

    /**
     * Get a Books entity
     * @Secure(roles="ROLE_READER")
     * @View(serializerEnableMaxDepthChecks=true)
     * @return Response
     *
     */
    public function getAction(Books $entity)
    {
        return $entity;
    }

    /**
     * Get all Books entities.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_READER")
     *
     * @param ParamFetcherInterface $paramFetcher
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


            $entities = $this->booksRepository->findBy($filters, $order_by, $limit, $offset);
            if ($entities) {
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create a Books entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_EXPERT")
     * @param Request $request
     *
     * @return Response
     *
     */
    public function postAction(Request $request)
    {
        $entity = new Books();
        $form = $this->createForm(new BooksType(), $entity, array("method" => $request->getMethod()));
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
     * Update a Books entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_EXPERT")
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function postUpdateAction(Request $request, Books $entity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            //$request->setMethod('POST'); //Treat all PUTs as PATCH
            $form = $this->createForm(new BooksType(), $entity, array("method" => $request->getMethod()));
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
    public function postVoteAction(Request $request, Books $entity) {
        try {
            $em = $this->getDoctrine()->getManager();
            /**@var $readBook */
            $readBook = $em->getRepository('CatalogBundle:ReadlistsBooks')->findBy([
                    'book' => $entity->getId(),
                    'readlist' => $em->getRepository('CatalogBundle:Readlists')->findBy(
                        ['user' => $this->getUser()->getId(), 'type' => ReadlistEnumType::READED]
                    )
                ]
            );
            if (!$readBook) {
                return FOSView::create(array('errors' => ['cannot voted']), Codes::HTTP_FORBIDDEN);
            }
            $vote = new \Library\VotesBundle\Entity\Vote();
            if(! $rate = $entity->getRating()) {
                $rate = new Rating();
                $entity->setRating($rate);
                $em->persist($rate);
            }
            $voteManager = $this->container->get('dcs_rating.manager.vote');
            if ($voteManager->findBy(['voter' => $this->getUser(), 'rating' => $rate])) {
                return FOSView::create(array('errors' => ['already voted']), Codes::HTTP_INTERNAL_SERVER_ERROR);
            }
            $vote->setRating($rate);
            $form = $this->createForm(new VoteType(), $vote, array("method" => $request->getMethod()));
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $vote->setVoter($this->getUser());
                $voteManager->saveVote($vote);
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
    public function postCommentAction(Request $request, Books $entity) {
        try {
            if(! $thread = $entity->getThread()) {
                $threadManager = $this->container->get('fos_comment.manager.thread');
                $thread = $threadManager->createThread();
                $thread->setCommentable(true);
                $entity->setThread($thread);
                $threadManager->saveThread($thread);
            }
            if (!$thread->isCommentable()) {
                return FOSView::create(null, Codes::HTTP_FORBIDDEN);
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
    public function putVoteAction(Request $request, Books $entity, Vote $vote) {
        try {
            if (!$rate = $entity->getRating()) {
                return FOSView::create(null, 404);
            }
            if ($vote->getVoter()->getId() !== $this->getUser()->getId()) {
                return FOSView::create(null, Codes::HTTP_FORBIDDEN);
            }

            $form = $this->createForm(new VoteType(), $vote, array("method" => $request->getMethod()));
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $voteManager = $this->container->get('dcs_rating.manager.vote');
                $voteManager->saveVote($vote);
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
    public function putCommentAction(Request $request, Books $entity, Comment $comment) {
        try {
            $em = $this->getDoctrine()->getManager();
            if (!$thread = $entity->getThread()) {
                return FOSView::create(null, 404);
            }
            if ($comment->getAuthor()->getId() !== $this->getUser()->getId()) {
                return FOSView::create(null, Codes::HTTP_FORBIDDEN);
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
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Update a Books entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_EXPERT")
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putAction(Request $request, Books $entity)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            $form = $this->createForm(new BooksType(), $entity, array("method" => $request->getMethod()));
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
     * Partial Update to a Books entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_EXPERT")
     * @param Request $request
     * @param $entity
     *
     * @return Response
*/
    public function patchAction(Request $request, Books $entity)
    {
        return $this->putAction($request, $entity);
    }
    /**
     * Delete a Books entity.
     *
     * @View(statusCode=204)
     * @Secure(roles="ROLE_EXPERT")
     * @param Request $request
     * @param $entity
     * @internal param $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, Books $entity)
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
