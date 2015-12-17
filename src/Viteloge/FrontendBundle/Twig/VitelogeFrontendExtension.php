<?php

namespace Viteloge\FrontendBundle\Twig {

    use Viteloge\CoreBundle\Component\Enum\TransactionEnum;

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
                new \Twig_SimpleFunction('aws_s3_domain', array($this, 'awsS3Domain'))
            );
        }

        /**
         *
         */
        public function getFilters() {
            return array(
                new \Twig_SimpleFilter('schematizedcurrency', array($this, 'schematizedcurrency')),
                new \Twig_SimpleFilter('vl_intval', array($this, 'vlIntval')),
                new \Twig_SimpleFilter('vl_transaction_alias', array($this, 'vlTransactionAlias')),
                new \Twig_SimpleFilter('to_latin1', array($this, 'toLatin1')),
                new \Twig_SimpleFilter('to_utf8', array($this, 'toUTF8')),
                new \Twig_SimpleFilter('fix_utf8', array($this, 'fixUTF8')),
                new \Twig_SimpleFilter('normalize_utf8', array($this, 'normalizeUTF8'))
            );
        }

        /**
         *
         */
        public function vlTheme() {
            $transaction = $this->vlTransactionAlias(strtoupper($this->request->get('transaction')));
            switch ($transaction) {
                case TransactionEnum::SALE:
                    $theme = 'sale';
                    break;

                case TransactionEnum::NEWER;
                    $theme = 'new';
                    break;

                case TransactionEnum::RENT:
                    $theme = 'rent';
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
        public function awsS3Domain($path, $compress=true) {
            $protocol = 'http';
            $suffix = $compress ? '.gz' : '';
            return $protocol.'://'.$this->container->getParameter('media_domain').'/'.$path.$suffix;
        }

        /**
         *
         */
        public function vlIntval($value) {
            $value = $this->vlTransactionAlias($value);
            switch ($value) {
                case TransactionEnum::RENT:
                    $result = 0;
                    break;
                case TransactionEnum::SALE:
                    $result = 1;
                    break;
                case TransactionEnum::NEWER:
                    $result = 2;
                    break;
                default:
                    $result = 3;
                    break;
            }
            return $result;
        }

        /**
         *
         */
        public function vlTransactionAlias($value) {
            return (strlen($value)>1) ? TransactionEnum::getAlias($value) : $value;
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
        public function toUTF8($value) {
            return \ForceUTF8\Encoding::toUTF8($value);
        }

        /**
         *
         */
        public function fixUTF8($value) {
            return \ForceUTF8\Encoding::fixUTF8($value);
        }

        /**
         *
         */
        public function toLatin1($value) {
            return \ForceUTF8\Encoding::toLatin1($value);
        }

        /**
         * Last chance to get a correct utf8 string
         */
        public function normalizeUTF8($value) {
            $search = array('â??', 'Â?');
            $replace = array('\'', '€');
            return trim(str_replace($search, $replace, $this->fixUTF8($value)));
        }

        /**
         *
         */
        public function getName() {
            return 'viteloge_frontend_extension';
        }

    }

}

