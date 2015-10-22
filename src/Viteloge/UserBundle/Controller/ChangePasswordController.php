<?php

namespace Viteloge\UserBundle\Controller {

    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use FOS\UserBundle\Controller\ChangePasswordController as BaseController;

    class ChangePasswordController extends BaseController {

        /**
         * Change user password
         */
        public function changePasswordAction(Request $request) {
            $translated = $this->get('translator');

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array(), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.user.changepassword.changepassword.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.user.changepassword.changepassword.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.user.changepassword.changepassword.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.user.changepassword.changepassword.description'))
                ->setLinkCanonical($canonicalLink)
            ;
            // --

            return parent::changePasswordAction($request);
        }

    }

}

