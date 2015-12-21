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
    use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
    use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
    use Symfony\Component\Security\Acl\Permission\MaskBuilder;
    use Pagerfanta\Pagerfanta;
    use Pagerfanta\Adapter\ArrayAdapter;
    use Pagerfanta\Adapter\DoctrineORMAdapter;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Viteloge\CoreBundle\Entity\WebSearch;
    use Viteloge\CoreBundle\Entity\UserSearch;

    /**
     * @Route("/user/search")
     */
    class UserSearchController extends Controller {

        /**
         * Creates a form to delete a UserSearch entity by id.
         *
         * @param UserSearch the object
         * @return \Symfony\Component\Form\Form The form
         */
        private function createDeleteForm(UserSearch $userSearch) {
            $id = $userSearch->getId();
            $method = 'DELETE';
            $action = 'viteloge_frontend_usersearch_delete';
            return $this->createFormBuilder()
                ->setAction($this->generateUrl($action, array('id' => $id)))
                ->setMethod($method)
                ->add('submit', 'submit', array('label' => 'usersearch.delete'))
                ->getForm()
            ;
        }

        /**
         * Display userSearches for a city
         * Private cache because of user informations
         *
         * @Route(
         *      "/city/{name}/{id}/{page}/{limit}",
         *      requirements={
         *          "id"="(?:2[a|b|A|B])?0{0,2}\d+",
         *          "page"="\d+",
         *          "limit"="\d+"
         *      },
         *      defaults={
         *          "page" = "1",
         *          "limit" = "50"
         *      },
         *      name="viteloge_frontend_usersearch_city",
         *      options = {
         *         "i18n" = true
         *      }
         * )
         * @Method({"GET", "POST"})
         * @ParamConverter(
         *     "inseeCity",
         *     class="AcreatInseeBundle:InseeCity",
         *     options={
         *         "id" = "id",
         *         "name" = "name",
         *         "exclude": {
         *             "name"
         *         }
         *     }
         * )
         * @Cache(expires="tomorrow", public=false)
         * @Template()
         */
        public function cityAction(Request $request, InseeCity $inseeCity, $page, $limit) {
            $translated = $this->get('translator');

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), $request->get('_route_params'), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle('viteloge.frontend.usersearch.city.title')
                ->addMeta('name', 'description', 'viteloge.frontend.usersearch.city.description')
                ->addMeta('name', 'robots', 'index, follow')
                ->addMeta('property', 'og:title', "viteloge.frontend.usersearch.city.title")
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', 'viteloge.frontend.usersearch.city.description')
                ->setLinkCanonical($canonicalLink)
            ;
            // --

            // Breadcrumbs
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_homepage')
            );
            if ($inseeCity->getInseeState()){
                $breadcrumbs->addItem(
                    $inseeCity->getInseeState()->getFullname(),
                    $this->get('router')->generate('viteloge_frontend_glossary_showstate',
                        array(
                            'name' => $inseeCity->getInseeState()->getSlug(),
                            'id' => $inseeCity->getInseeState()->getId()
                        )
                    )
                );
            }
            if ($inseeCity->getInseeDepartment()) {
                $breadcrumbs->addItem(
                    $inseeCity->getInseeDepartment()->getFullname(),
                    $this->get('router')->generate('viteloge_frontend_glossary_showdepartment',
                        array(
                            'name' => $inseeCity->getInseeDepartment()->getSlug(),
                            'id' => $inseeCity->getInseeDepartment()->getId()
                        )
                    )
                );
            }
            $breadcrumbs->addItem(
                $inseeCity->getFullname(),
                $this->get('router')->generate('viteloge_frontend_glossary_showcity',
                    array(
                        'name' => $inseeCity->getSlug(),
                        'id' => $inseeCity->getId()
                    )
                )
            );
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.usersearch.city', array('%city%' => $inseeCity->getName()), 'breadcrumbs')
            );
            // --

            $em = $this->getDoctrine()->getManager();
            $queryBuilder = $em->createQueryBuilder()
                ->select('us.transaction, us.type, us.rooms, us.budgetMin, us.budgetMax, ic.id inseeCity')
                ->distinct()
                ->from('VitelogeCoreBundle:UserSearch', 'us')
                ->leftJoin('us.inseeCity', 'ic')
                ->where('us.inseeCity = :inseeCity')
                ->andWhere('us.deletedAt IS NULL')
                ->orderBy('us.createdAt', 'DESC')
                ->setParameter('inseeCity', $inseeCity)
            ;

            $adapter = new DoctrineORMAdapter($queryBuilder, true, false);
            $pagination = new Pagerfanta($adapter);
            $pagination->setCurrentPage($page);
            $pagination->setMaxPerPage($limit);

            return array(
                'inseeCity' => $inseeCity,
                'userSearches' => $pagination->getCurrentPageResults(),
                'pagination' => $pagination
            );
        }

        /**
         * Remove userSearch. Set the deletedAt field to "now"
         * No cache
         *
         * @Route(
         *      "/delete/{id}",
         *      requirements={
         *          "id"="\d+"
         *      },
         *      name="viteloge_frontend_usersearch_delete",
         *      options = {
         *         "i18n" = true
         *      }
         * )
         * @Method("DELETE")
         * @Security("has_role('ROLE_USER')")
         * @ParamConverter("userSearch", class="VitelogeCoreBundle:UserSearch", options={"id" = "id"})
         * Template("VitelogeFrontendBundle:UserSearch:delete.html.twig")
         */
        public function deleteAction(Request $request, $userSearch) {
            $translated = $this->get('translator');

            $form = $this->createDeleteForm($userSearch);
            $form->handleRequest($request);

            if ($form->isValid()) {
                $userSearch->setDeletedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($userSearch);
                $em->flush();
                $this->addFlash(
                    'notice',
                    $translated->trans('usersearch.flash.deleted')
                );
            }

            return $this->redirectToRoute('viteloge_frontend_websearch_list');
        }

        /**
         * Legacy. Remove userSearch from mail. Set the deletedAt field to "now"
         * No cache
         *
         * @Route(
         *      "/delete/from/mail/{timestamp}/{token}/{id}",
         *      requirements={
         *          "timestamp"="\d+",
         *          "token"=".+",
         *          "id"="\d+"
         *      },
         *      name="viteloge_frontend_usersearch_deletefrommail",
         *      options = {
         *         "i18n" = true
         *      }
         * )
         * @Method("GET")
         * @ParamConverter("webSearch", class="VitelogeCoreBundle:WebSearch", options={"id" = "id"})
         */
        public function deleteFromMailAction(Request $request, $timestamp, $token, $webSearch) {
            $translated = $this->get('translator');
            $now = time();
            if ($timestamp < $now && ($timestamp > ($now-604800))) {
                $user = $webSearch->getUser();
                $userSearch = $webSearch->getUserSearch();

                $key = $timestamp.':'.$userSearch->getMail();
                $newTokenManager = $this->get('viteloge_frontend.mail_token_manager');
                $oldTokenManager = $this->get('viteloge_frontend.old_token_manager');
                $newTokenManager->setUser($user)->hashBy($key);
                $oldTokenManager->setUser($user)->hashBy($key);

                if (!$newTokenManager->isTokenValid($token) && !$oldTokenManager->isTokenValid($token)) {
                    throw $this->createNotFoundException();
                }

                $userSearch->setDeletedAt(new \DateTime());
                $em = $this->getDoctrine()->getManager();
                $em->persist($userSearch);
                $em->flush();
                $this->addFlash(
                    'notice',
                    $translated->trans('usersearch.flash.deleted')
                );

                return $this->redirectToRoute('viteloge_frontend_homepage');
            } else {
                throw $this->createNotFoundException();
            }
        }

    }

}
