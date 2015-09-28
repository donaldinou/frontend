<?php

namespace Acreat\InseeBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Acreat\InseeBundle\Entity\InseeArea;

    /**
     * @Route("/insee-area")
     */
    class AreaController extends Controller {

        /**
         * @Route(
         *      "/show/{id}.{_format}",
         *      requirements={
         *          "id"="\d+",
         *          "_format"="html|json"
         *      },
         *      defaults={
         *          "_format"="json"
         *      },
         *      name="acreat_insee_area_show"
         * )
         * @ParamConverter("inseeArea", class="AcreatInseeBundle:InseeArea", options={"id" = "id"})
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         */
        public function showAction(Request $request, $_format, InseeArea $inseeArea) {
            //if ($request->isXmlHttpRequest()) {
                $inseeArea->getInseeCity()->setInseeState(null); // we do not need insee city
                $inseeArea->getInseeCity()->setInseeDepartment(null); // we do not need insee city
                //$serializer = $this->get('jms_serializer');
                //$inseeArea = $serializer->serialize($inseeArea, $_format);
            //}
            return $this->render(
                'AcreatInseeBundle:Area:show.'.$_format.'.twig',
                array(
                    'inseeArea' => $inseeArea
                )
            );
        }

    }

}
