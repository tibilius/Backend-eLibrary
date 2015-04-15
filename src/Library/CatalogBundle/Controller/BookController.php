<?php

namespace LibraryCatalogBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\Validator\ConstraintViolationList;
use library\CatalogBundle\Entity\Book;


class BookController extends FOSRestController
{

    /**
     * Create new book from POST data
     *
     * @Secure(roles="ROLE_API")
     * @ApiDoc(
     *  resource = true,
     *  description = "Create new book from POST data",
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returned when the book isn't found"
     *  }
     * )
     *
     * @param ParamFetcher $paramFetcher ParamFetcher
     *
     * @RequestParam(name="id", nullable=false, strict=true, description="id.")
     * @RequestParam(name="name", nullable=false, strict=true, description="Book name.")
     * @RequestParam(name="description", nullable=true, strict=true, description="Book description.")
     * @RequestParam(name="category_id", nullable=false, strict=true, description="Category identifier.")
     *
     *
     * @return View
     */
    public function postBookAction(ParamFetcher $paramFetcher){

        /**
         * @var \Library\CatalogBundle\Entity\Book $entity
         */

        $entity = new Book();
        $entity->setName($paramFetcher->get("name"));
        $categoryRefence = $this->getDoctrine()->getManager()->getReference("CatalogBundle:Category", $paramFetcher->get("category_id"));
        $entity->setCategory($categoryRefence);

        if($paramFetcher->get("description")){
            $entity->setDescription($paramFetcher->get("description"));
        }
        $view = View::create();
        $errors = $this->get('validator')->validate($entity);
        if (count($errors) > 0) {
            $view = $this->getErrorsView($errors);
        } else {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            $view->setData($entity)->setStatusCode(200);
        }
        return $view;
    }


    /**
     * Return a book identified by id
     *
     * @Secure(roles="ROLE_API")
     * @ApiDoc(
     *  resource = true,
     *  description = "Return a book identified by id",
     *  statusCodes = {
     *      200 = "Returned when successful",
     *      404 = "Returned when the book isn't found"
     *  }
     * )
     *
     * @param int $id book identifier
     *
     * @return View
     */
    public function getBookAction($id){
        $entity = $this->getDoctrine()->getRepository('CatalogBundle:Book')->findOneBy(
            array("id" => $id )
        );

        if(!is_object($entity)){
            throw $this->createNotFoundException();
        }

        $view = View::create();
        $view->setData($entity)->setStatusCode(200);

        return $view;
    }

    /**
     * Update book by id
     * @Secure(roles="ROLE_API")
     * @ApiDoc(
     *  resource = true,
     *  description = "Update book identified by ID",
     *  statusCodes = {
     *      200 = "Book updated",
     *      404 = "Book not found by id"
     *  }
     * )
     * @param ParamFetcher $paramFetcher
     *
     * @RequestParam(name="id", nullable=false, strict=true, description="id.")
     * @RequestParam(name="name", nullable=true, strict=true, description="Book name.")
     * @RequestParam(name="category_id", nullable=true, strict=true, description="Category identifier.")
     *
     * @return View
     */

    public function putBookAction(ParamFetcher $paramFetcher){

        /**
         * @var \Library\CatalogBundle\Entity\Book $entity
         */

        $entity = $this->getDoctrine()->getRepository('CatalogBundle:Book')->findOneBy(Array(
            "id" => $paramFetcher->get("id")
        ));
        if($paramFetcher->get("name")){ $entity->setName($paramFetcher->get("name")); }
        if($paramFetcher->get("category_id")){
            $categoryRefence = $this->getDoctrine()->getManager()->getReference("CatalogBundle:Category", $paramFetcher->get("category_id"));
            $entity->setCategory($categoryRefence);
        }

        $request = $this->getRequest();

        $view = View::Create();
        $view->setData($entity)->setStatusCode(200);
        return $view;
    }

    /**
     * Delete a book identified by id.
     *
     * @Secure(roles="ROLE_API")
     * @ApiDoc(
     *   resource = true,
     *   description = "Delete an user identified by username/email",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @param string $slug username or email
     *
     * @return View
     */
    public function deleteBookAction($slug)
    {
        /**
         * @todo add Logic for This action
         */
        $userManager = $this->container->get('fos_user.user_manager');
        $entity = $userManager->findUserByUsernameOrEmail($slug);

        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }

        $userManager->deleteUser($entity);

        $view = View::create();
        $view->setData("User deteled.")->setStatusCode(204);

        return $view;
    }

    /**
     * Vote for a book
     *
     * @Secure(roles="ROLE_API")
     * @ApiDoc(
     *   resource = true,
     *   description = "Vote for a book",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @RequestParam(name="id", nullable=false, strict=true, description="id.")
     * @RequestParam(name="vote", nullable=false, strict=true, description="vote")
     * @return View
     */
    public function voteBookAction(ParamFetcher $paramFetcher)
    {
        $book_id = $paramFetcher->get('id');
        $vote = $paramFetcher->get('vote');
        $entity = $this->getDoctrine()->getRepository('CatalogBundle:Books')->find($book_id);
        /**@var $entity \Library\CatalogBundle\Entity\Books */
        $entity->getRating();
        $view = View::create();
        $view->setData("User deteled.")->setStatusCode(204);

        return $view;
    }


}