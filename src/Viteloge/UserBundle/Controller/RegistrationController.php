<?php

namespace Viteloge\UserBundle\Controller {

    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use FOS\UserBundle\Controller\RegistrationController as BaseController;

    /**
     *
     */
    class RegistrationController extends BaseController {

        /**
         * @see BaseController
         */
        public function registerAction(Request $request) {
            $translated = $this->get('translator');

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array(), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.user.registration.register.title'))
                ->addMeta('name', 'description', $translated->trans('viteloge.user.registration.register.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.user.registration.register.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.user.registration.register.description'))
                ->setLinkCanonical($canonicalLink)
            ;
            // --

            return parent::registerAction($request);
        }

        /**
         * @see BaseController
         */
        public function confirmedAction() {
            $translated = $this->get('translator');

            // Breadcrumbs
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.home', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_homepage')
            );
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.user', array(), 'breadcrumbs'),
                $this->get('router')->generate('viteloge_frontend_user_index')
            );
            $breadcrumbs->addItem(
                $translated->trans('breadcrumb.registration.success', array(), 'breadcrumbs')
            );
            // --

            return parent::confirmedAction();
        }

    }

}
