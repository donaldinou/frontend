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
                new \Twig_SimpleFunction('vl_theme', array($this, 'vlTheme'))
            );
        }

        /**
         *
         */
        public function getFilters() {
            return array(
                new \Twig_SimpleFilter('schematizedcurrency', array($this, 'schematizedcurrency'))
            );
        }

        /**
         *
         */
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
        public function schematizedcurrency($number, $currency=null, $locale=null) {
            $formatter = twig_get_number_formatter($locale, 'currency');
            $result = $formatter->formatCurrency($number, $currency);
            preg_match('/([\p{Sc}]*)[\p{Zs}]*([\p{N}\p{Po}\p{Zs}]*)([\p{Sc}]*)/u', $result, $matches);
            if (count($matches) === 4) {
                $price = $matches[2];
                if (!empty($matches[1])) {
                    $symbol = $matches[1];
                    $result = '<span itemprop="priceCurrency" content="'.$currency.'">'.$symbol.'</span>';
                    $result .= '<span itemprop="price" content="'.trim($price, " \t\n\r\0\x0B".chr(0xC2).chr(0xA0)).'">'.$price.'</span>';
                } else {
                    $symbol = $matches[3];
                    $result = '<span itemprop="price" content="'.trim($price, " \t\n\r\0\x0B".chr(0xC2).chr(0xA0)).'">'.$price.'</span>';
                    $result .= '<span itemprop="priceCurrency" content="'.$currency.'">'.$symbol.'</span>';
                }
            }
            return $result;
        }

        /**
         *
         */
        public function getName() {
            return 'viteloge_frontend_extension';
        }

    }

}

