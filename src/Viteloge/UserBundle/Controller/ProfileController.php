<?php

namespace Viteloge\UserBundle\Controller {

    use Symfony\Component\HttpFoundation\RedirectResponse;
    use Symfony\Component\HttpFoundation\Request;
    use FOS\UserBundle\Controller\ProfileController as BaseController;

    class ProfileController extends BaseController {

        public function showAction() {
            $breadcrumbs = $this->get('white_october_breadcrumbs');
            $breadcrumbs->addItem('Home', $this->get('router')->generate('viteloge_frontend_homepage'));
            $breadcrumbs->addItem('User', $this->get('router')->generate('viteloge_frontend_user_index'));
            $breadcrumbs->addItem('Profile');

            return parent::showAction();
        }

    }

}
