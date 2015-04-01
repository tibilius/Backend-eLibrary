<?php

namespace library\UserBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as FosView;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\Prefix;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Head;
use FOS\RestBundle\Controller\Annotations\Delete;
use SU\CommonBundle\Library\Event;
use SU\CommonBundle\Library\RestResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AuthController
 * @package library\UserBundle\Controller
 * @Prefix("/auth/login")
 *
 */

class AuthController extends FOSRestController
{

    /**
     * @ApiDoc(
     *  resource=true,
     *  description="User Login interface"
     * )
     * @POST("")
     * @RequestParam(name="login", requirements="^[a-zA-Z0-9]+$", nullable=false)
     * @param ParamFetcherInterface $paramFetcher
     * @return Response
     */
    public function loginAction(ParamFetcherInterface $paramFetcher)
    {
        $this->container->get('')->loginUser();
        return $paramFetcher->all();
    }

}
