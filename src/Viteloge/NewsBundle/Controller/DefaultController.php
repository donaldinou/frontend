<?php

namespace Viteloge\NewsBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Acreat\InseeBundle\Entity\InseeDepartment;
    use Acreat\InseeBundle\Entity\InseeState;

    /**
     *
     * @Route("/news")
     */
    class DefaultController extends Controller {

        /**
         * @Route(
         *     "/{city-slug}/{id}",
         *     requirements={
         *         "id"="\d+"
         *     },
         *     name="viteloge_news_default_city"
         * )
         * Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeNewsBundle:Default:city.html.twig")
         */
        public function cityAction($name){
            return array(

            );
        }

    }

}
