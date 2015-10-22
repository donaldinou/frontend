<?php

namespace Viteloge\CoreBundle\Twig {

    use Viteloge\CoreBundle\Entity\User;

    class VitelogeCoreExtension extends \Twig_Extension {

        public function __construct() {

        }

        /**
         *
         */
        public function getFunctions() {
            return array(
                new \Twig_SimpleFunction('calculate_ratio_profile', array($this, 'calculateRatioProfile')),
            );
        }

        /**
         *
         */
        public function getFilters() {
            return array(

            );
        }

        /**
         *
         */
        public function calculateRatioProfile($user) {
            $percent = 100;
            if ($user instanceof User) {
                $percent = $user->calculateRatioProfile();
            }
            return $percent;
        }

        /**
         *
         */
        public function getName() {
            return 'viteloge_core_extension';
        }

    }

}

