<?php

namespace Library\UserBundle\Controller;

use Library\UserBundle\Entity\User;
use Library\UserBundle\Form\UserSelfEditType;
use Library\UserBundle\Form\UserType;

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
use JMS\SecurityExtraBundle\Annotation\Secure;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


use Voryx\RESTGeneratorBundle\Controller\VoryxController;
/**
 * User controller.
 * @RouteResource("User")
 */
class UserRESTController extends VoryxController
{

    /**
     * Return the overall user list.
     *
     * @Secure(roles="ROLE_GUEST")
     * @ApiDoc(
     *   resource = true,
     *   description = "Return the current loginned user",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     401 = "Returned when the user is not found"
     *   }
     * )
     *
     * @return View
     */
    public function getWhoamiAction() {
        $currentUser = $this->getUser();
        $user = $this->container->get('fos_user.user_manager')->findUserBy(['id'=>$currentUser->getId()]);
        $view = FOSView::create($user, 200);
        return $view;
    }

    /**
     * Get User Salt.
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Get user salt by its username",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @param string $id the user username
     *
     * @return View
     */
    public function getSaltAction($slug)
    {
        $entity = $this->getDoctrine()->getRepository('Library\UserBundle\Entity\User')->findOneBy(
            ['username' => strtolower($slug)]
        );

        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }
        return FOSView::create(['salt' => $entity->getSalt()], 200);
    }

    /**
     * Get a User entity
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_GUEST")
     * @return Response
     *
     */
    public function getAction(User $entity)
    {
        return $entity;
    }

    /**
     * Get all User entities.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param ParamFetcherInterface $paramFetcher
     * @Secure(roles="ROLE_SUPER_ADMIN")
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
            $entities = $em->getRepository('UserBundle:User')->findBy($filters, $order_by, $limit, $offset);
            if ($entities) {
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Create a User entity.
     *
     * @View(statusCode=201, serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_SUPER_ADMIN")
     *
     * @param Request $request
     *
     * @return Response
     *
     */
    public function postAction(Request $request)
    {
        $entity = new User();
        $form = $this->createForm(new UserType(), $entity, array("method" => $request->getMethod()));
        $this->removeExtraFields($request, $form);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('fos_user.user_manager')->updateUser($entity);
            return $entity;
        }

        return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Update a User entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_GUEST")
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function putAction(Request $request, User $entity)
    {
        try {
            $request->setMethod('PATCH'); //Treat all PUTs as PATCH
            if ($this->getUser()->hasRole('ROLE_SUPER_ADMIN')){
                $form = $this->createForm(new UserType(), $entity, array("method" => $request->getMethod()));
            }
            elseif($this->getUser()->getId() == $entity->getId()){
                $form = $this->createForm(new UserSelfEditType(), $entity, array("method" => $request->getMethod()));
            }
            else {
                return FOSView::create(null, 403);
            }
            $this->removeExtraFields($request, $form);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this->get('fos_user.user_manager')->updateUser($entity);
                return $entity;
            }

            return FOSView::create(array('errors' => $form->getErrors()), Codes::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update a User entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_GUEST")
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
     */
    public function postUpdateAction(Request $request, User $entity)
    {
       return $this->putAction($request, $entity);
    }

    /**
     * Update a User entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postResetAction(Request $request)
    {
        try {
            $user = $this->container->get('fos_user.user_manager')->findUserByUsernameOrEmail($request->get('email'));
            if (!$user) {
                return FOSView::create(null, 404);
            }
            $plainPassword = substr(md5(microtime()), 0, 8);
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $this->container->get('fos_user.user_manager')->updateUser($user);
            $this->container->get('library_user_mailer')->sendNewPasswordMessage($user, $plainPassword);
            return FOSView::create($user, Codes::HTTP_OK);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    /**
     * Partial Update to a User entity.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_GUEST")
     *
     * @param Request $request
     * @param $entity
     *
     * @return Response
    */
    public function patchAction(Request $request, User $entity)
    {
        return $this->putAction($request, $entity);
    }

    /**
     * Delete a User entity.
     *
     * @View(statusCode=204)
     * @Secure(roles="ROLE_SUPER_ADMIN")
     *
     * @param Request $request
     * @param $entity
     * @internal param $id
     *
     * @return Response
     */
    public function deleteAction(Request $request, User $entity)
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
