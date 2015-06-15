<?php

namespace Viteloge\FrontendBundle\Pagerfanta\View {

    use Pagerfanta\PagerfantaInterface;
    use Pagerfanta\View\ViewInterface;
    use Symfony\Component\Translation\TranslatorInterface;

    class VitelogeViewTranslated extends VitelogeView {

        /**
         * Constructor.
         *
         * @param ViewInterface  $view A view.
         * @param TranslatorInterface $translator A translator interface.
         */
        public function __construct(ViewInterface $view, TranslatorInterface $translator) {
            $this->view = $view;
            $this->translator = $translator;
        }

        public function getName() {
            return 'viteloge_translated';
        }

    }

}
