<?php

namespace Viteloge\UserBundle\Controller {

    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use FOS\UserBundle\Controller\ResettingController as BaseController;

    class ResettingController extends BaseController {

        public function requestAction() {
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
                $translated->trans('breadcrumb.reset', array(), 'breadcrumbs')
            );
            // --

            return parent::requestAction();
        }

    }

}
