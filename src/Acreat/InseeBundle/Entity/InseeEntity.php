<?php

namespace Acreat\InseeBundle\Entity {

    class InseeEntity {

        protected $prefix;

        protected $name;

        /**
         * Return the hinge prefix as it is specified in documentation
         * @see http://www.insee.fr/fr/methodes/nomenclatures/cog/documentation.asp?page=telechargement/2015/doc/doc_variables.htm#tncc
         * @return string
         */
        public function getHingePrefix($isCapitalize=false) {
            $result = $this->prefix;
            if (is_numeric($this->prefix)) {
                switch ((int)$this->prefix) {
                    case 1:
                        $result = 'd\'';
                        break;
                    case 2:
                        $result = 'du';
                        break;
                    case 3:
                        $result = 'de la';
                        break;
                    case 4:
                        $result = 'des';
                        break;
                    case 5:
                        $result = 'de l\'';
                        break;
                    case 6:
                        $result = 'des';
                        break;
                    case 7:
                        $result = 'de las';
                        break;
                    case 8:
                        $result = 'de los';
                        break;
                    case 0:
                    default:
                        $result = 'de';
                        break;
                }
            }
            return ($isCapitalize) ? ucfirst($result) : $result;
        }

        /**
         * Return the article prefix as it is specified in documentation
         * @see http://www.insee.fr/fr/methodes/nomenclatures/cog/documentation.asp?page=telechargement/2015/doc/doc_variables.htm#tncc
         * @return string
         */
        public function getArticlePrefix($isCapitalize=true) {
            $result = $this->prefix;
            if (is_numeric($this->prefix)) {
                switch ((int)$this->prefix) {
                    case 1:
                        $result = '';
                        break;
                    case 2:
                        $result = 'le';
                        break;
                    case 3:
                        $result = 'la';
                        break;
                    case 4:
                        $result = 'les';
                        break;
                    case 5:
                        $result = 'l\'';
                        break;
                    case 6:
                        $result = 'aux';
                        break;
                    case 7:
                        $result = 'las';
                        break;
                    case 8:
                        $result = 'los';
                        break;
                    case 0:
                    default:
                        $result = '';
                        break;
                }
            }
            return ($isCapitalize) ? ucfirst($result) : $result;
        }

        /**
         * Return the entire name
         *
         * @return string
         */
        public function getFullname() {
            return (!empty($this->getArticlePrefix())) ? $this->getArticlePrefix().' '.$this->getName() : $this->getName();
        }

        /**
         * Return the entire name with the hinge prefix
         * @return string
         */
        public function getHingeFullname() {
            return (!empty($this->getHingePrefix())) ? $this->getHingePrefix().' '.$this->getName() : $this->getName();
        }

        /**
         *
         */
        public function getPrefix() {
            return $this->prefix;
        }

        /**
         *
         */
        public function getName() {
            return $this->name;
        }

        /**
         *
         */
        public function __toString() {
            return (string)$this->getFullname();
        }

    }


}
