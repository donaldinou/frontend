<?php

namespace Viteloge\UserBundle\Controller {

    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use FOS\UserBundle\Controller\ProfileController as BaseController;

    class ProfileController extends BaseController {

        public function showAction() {
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
                $translated->trans('breadcrumb.profile', array(), 'breadcrumbs')
            );
            // --

            // SEO
            $canonicalLink = $this->get('router')->generate($this->getRequest()->get('_route'), array(), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.user.profile.show.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.user.profile.show.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.user.profile.show.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.user.profile.show.description'))
                ->setLinkCanonical($canonicalLink)
            ;
            // --

            return parent::showAction();
        }

        public function editAction(Request $request) {
            $translated = $this->get('translator');

            // SEO
            $canonicalLink = $this->get('router')->generate($request->get('_route'), array(), true);
            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($translated->trans('viteloge.user.profile.show.title'))
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $translated->trans('viteloge.user.profile.show.description'))
                ->addMeta('property', 'og:title', $translated->trans('viteloge.user.profile.show.title'))
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $translated->trans('viteloge.user.profile.show.description'))
                ->setLinkCanonical($canonicalLink)
            ;
            // --

            return parent::editAction($request);
        }

    }

}
