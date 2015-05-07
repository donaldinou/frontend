<?php

namespace Viteloge\FrontendBundle\Twig {

    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;

    class VitelogeFrontendExtension extends \Twig_Extension {

        protected $container;

        protected $request;

        public function __construct($container) {
            $this->container = $container;
            if ($this->container->isScopeActive('request')) {
                $this->request = $this->container->get('request');
            }
        }

        /**
         *
         */
        public function getFunctions() {
            return array(
                new \Twig_SimpleFunction('vl_theme', array($this, 'vlTheme')),
            );
        }

        public function vlTheme() {
            $transaction = $this->request->get('transaction');
            switch ($transaction) {
                case EnumTransactionType::SALE_VALUE:
                    $theme = EnumTransactionType::SALE_LABEL;
                    break;

                case EnumTransactionType::NEWER_VALUE;
                    $theme = EnumTransactionType::NEWER_LABEL;
                    break;

                case EnumTransactionType::RENT_VALUE:
                    $theme = EnumTransactionType::RENT_LABEL;
                    break;

                default:
                    $theme = 'default';
                    break;
            }
            return strtolower('theme-'.$theme);
        }

        /**
         *
         */
        public function getName() {
            return 'viteloge_frontend_extension';
        }

    }

}

