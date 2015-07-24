<?php

namespace Viteloge\UserBundle\Controller {

    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use FOS\UserBundle\Controller\SecurityController as BaseController;

    class SecurityController extends BaseController {

        public function loginAction(Request $request) {
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
                $translated->trans('breadcrumb.login', array(), 'breadcrumbs')
            );
            // --

            return parent::loginAction($request);
        }

    }

}
