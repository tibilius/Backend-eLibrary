<?php

namespace Library\CatalogBundle\Controller;

use Library\CatalogBundle\Entity\Books;
use Library\CatalogBundle\Form\BooksType;

use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\View\View as FOSView;
use Library\CatalogBundle\Repository\BookRepository;
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
 * FTS controller.
 * @RouteResource("search")
 */
class FTSRESTController extends VoryxController
{
    /**
     * @DI\Inject("library_catalogbundle.repository.bookrepository")
     * @var BookRepository
     */
    protected $booksRepository;

    /**
     * Get Books entities.
     *
     * @View(serializerEnableMaxDepthChecks=true)
     * @Secure(roles="ROLE_GUEST")
     *
     * @param ParamFetcherInterface $paramFetcher
     * @return Response
     * @ApiDoc(
     *      resource=true,
     *      section="books"
     * )
     * @QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
     * @QueryParam(name="limit", requirements="\d+", default="20", description="How many notes to return.")
     * @QueryParam(name="query", default="", description="query for search")
     */
    public function getAction(ParamFetcherInterface $paramFetcher)
    {
        try {
            $offset = $paramFetcher->get('offset');
            $limit = $paramFetcher->get('limit');
            $query = $paramFetcher->get('query');
            $entities = $this->booksRepository->findByQuery($query, $limit, $offset);
            if ($entities) {
                return $entities;
            }

            return FOSView::create('Not Found', Codes::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return FOSView::create($e->getMessage(), Codes::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

 }
