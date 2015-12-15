<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\Form\FormError;
    use Viteloge\FrontendBundle\Entity\Recommand;
    use Viteloge\FrontendBundle\Form\Type\RecommandType;

    /**
     * Recommand controller.
     *
     * @Route("/recommand")
     */
    class RecommandController extends Controller {

        /**
         * Creates a form to create a Recommand entity.
         *
         * @param Recommand $recommand The entity
         * @return \Symfony\Component\Form\Form The form
         */
        private function createCreateForm(Recommand $recommand) {
            return $this->createForm(
                'viteloge_frontend_recommand',
                $recommand,
                array(
                    'action' => $this->generateUrl('viteloge_frontend_recommand_create'),
                    'method' => 'POST'
                )
            );
        }

        /**
         * Displays a form to create a new Recommand entity.
         * Private cache
         *
         * @Route(
         *      "/new",
         *      name="viteloge_frontend_recommand_new"
         * )
         * @Cache(expires="tomorrow", public=false)
         * @Method("GET")
         * @Template("VitelogeFrontendBundle:Recommand:new.html.twig")
         */
        public function newAction(Request $request) {
            // This count is pretty faster than an elastic search count
            $repository = $this->getDoctrine()
                ->getRepository('VitelogeCoreBundle:Ad');
            $count = $repository->countByFiltered();

            $recommand = new Recommand();
            $recommand->setUser($this->getUser());
            $recommand->setMessage('Pour trouver facilement un logement, va sur ViteLogé le moteur de recherche de l\'immobilier. Il y a actuellement plus de '.$count.' annonces sur toute la France.');
            $form = $this->createCreateForm($recommand);

            return array(
                'recommand' => $recommand,
                'form' => $form->createView(),
            );
        }

        /**
         * Creates a new Recommand entity.
         * No cache
         *
         * @Route(
         *      "/",
         *      name="viteloge_frontend_recommand_create"
         * )
         * @Method("POST")
         * @Template("VitelogeFrontendBundle:Recommand:new.html.twig")
         */
        public function createAction(Request $request) {
            $trans = $this->get('translator');
            $recommand = new Recommand();
            $recommand->setUser($this->getUser());
            $form = $this->createCreateForm($recommand);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $result = $this->sendMessage($recommand);
                if ($result) {
                    return $this->redirect($this->generateUrl('viteloge_frontend_recommand_success', array()));
                }
                $form->addError(new FormError($trans->trans('recommand.send.error')));
            }

            return array(
                'recommand' => $recommand,
                'form' => $form->createView(),
            );
        }

        /**
         *
         */
        protected function sendMessage(Recommand $recommand) {
            $trans = $this->get('translator');
            $from = array(
                $recommand->getEmail() => $recommand->getFullname()
            );
            $mail = \Swift_Message::newInstance()
                ->setSubject($trans->trans($recommand->getFullname().' Souhaite vous faire découvrir ViteLogé.com !'))
                ->setFrom($from)
                ->setTo($recommand->getEmails()->getValues())
                ->setBody(
                    $this->renderView(
                        'VitelogeFrontendBundle:Recommand:email/recommand.html.twig',
                        array(
                            'recommand' => $recommand
                        )
                    ),
                    'text/html'
                )
            ;
            return $this->get('mailer')->send($mail);
        }

        /**
         * Success
         *
         * @Route(
         *      "/success",
         *      name="viteloge_frontend_recommand_success"
         * )
         * @Cache(expires="tomorrow", public=false)
         * @Method("GET")
         * @Template("VitelogeFrontendBundle:Recommand:success.html.twig")
         */
        public function successAction() {
            $recommand = null;
            return array(
                'recommand' => $recommand
            );
        }

        /**
         * Fail
         *
         * @Route(
         *      "/fail",
         *      name="viteloge_frontend_recommand_fail"
         * )
         * @Cache(expires="tomorrow", public=false)
         * @Method("GET")
         * @Template("VitelogeFrontendBundle:Recommand:fail.html.twig")
         */
        public function failAction() {
            $recommand = null;
            return array(
                'recommand' => $recommand
            );
        }

    }


}
