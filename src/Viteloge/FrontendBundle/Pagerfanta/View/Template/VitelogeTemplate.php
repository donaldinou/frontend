<?php

namespace Viteloge\FrontendBundle\Pagerfanta\View\Template {

    use  Pagerfanta\View\Template\TwitterBootstrap3Template;

    /**
     *
     */
    class VitelogeTemplate extends TwitterBootstrap3Template {

        static protected $defaultOptions = array(
            'first_icon'          => '&laquo;',
            'prev_icon'           => '&lt;',
            'next_icon'           => '&gt;',
            'last_icon'           => '&raquo;',
            'first_message'       => 'First',
            'prev_message'        => 'Previous',
            'next_message'        => 'Next',
            'last_message'        => 'Last',
            'dots_message'        => '&hellip;',
            'active_suffix'       => '<span class="sr-only">(current)</span>',
            'css_container_class' => 'pagination ajax',
            'css_first_class'     => 'hidden-xs first',
            'css_last_class'      => 'hidden-xs last',
            'css_prev_class'      => 'hidden-xs previous',
            'css_next_class'      => 'hidden-xs next',
            'css_disabled_class'  => 'disabled',
            'css_dots_class'      => 'disabled',
            'css_active_class'    => 'active',
        );

        /**
         *
         */
        public function __construct() {
            parent::__construct();
        }

        /**
         *
         */
        public function container() {
            return sprintf(
                '<nav>'.
                    '<ul class="%s">'.
                        '%%pages%%'.
                    '</ul>'.
                '</nav>',
                $this->option('css_container_class')
            );
        }

        /**
         *
         */
        public function previousDisabled() {
            $rel = 'prev';
            $class = $this->option('css_prev_class').' '.$this->option('css_disabled_class');
            $text = $this->option('prev_message');
            $icon = $this->option('prev_icon');
            return $this->elementLi('span', '', $class, $rel, $text, $icon);
        }

        /**
         *
         */
        public function previousEnabled($page) {
            $rel = 'prev';
            $href = $this->generateRoute($page);
            $class = $this->option('css_prev_class');
            $text = $this->option('prev_message');
            $icon = $this->option('prev_icon');
            return $this->elementLi('a', $href, $class, $rel, $text, $icon);
        }

        /**
         *
         */
        public function nextDisabled() {
            $rel = 'next';
            $class = $this->option('css_next_class').' '.$this->option('css_next_class');
            $text = $this->option('next_message');
            $icon = $this->option('next_icon');
            return $this->elementLi('span', '', $class, $rel, $text, $icon);
        }

        /**
         *
         */
        public function nextEnabled($page) {
            $rel = 'next';
            $href = $this->generateRoute($page);
            $class = $this->option('css_next_class');
            $text = $this->option('next_message');
            $icon = $this->option('next_icon');
            return $this->elementLi('a', $href, $class, $rel, $text, $icon);
        }

        /**
         *
         */
        public function goFirst($page, $isEnabled=true) {
            $rel = 'first';
            $href = $this->generateRoute($page);
            $class = $this->option('css_first_class');
            $text = $this->option('first_message');
            $icon = $this->option('first_icon');
            $element = 'a';
            if (!$isEnabled) {
                $element = 'span';
                $class .= ' '.$this->option('css_disabled_class');
            }
            return $this->elementLi($element, $href, $class, $rel, $text, $icon);
        }

        /**
         *
         */
        public function goLast($page, $isEnabled=true) {
            $rel = 'next';
            $href = $this->generateRoute($page);
            $class = $this->option('css_last_class');
            $text = $this->option('last_message');
            $icon = $this->option('last_icon');
            $element = 'a';
            if (!$isEnabled) {
                $element = 'span';
                $class .= ' '.$this->option('css_disabled_class');
            }
            return $this->elementLi($element, $href, $class, $rel, $text, $icon);
        }

        /**
         *
         */
        private function elementLi($element, $href='', $class='', $rel='', $text='', $icon=null) {
            return
                '<li class="'.$class.'">'.
                    '<'.$element.
                        ((!empty($rel)) ? ' rel="'.$rel.'"' : '').
                        ((!empty($rel)) ? ' href="'.$href.'"' : '').
                        ((!empty($icon)) ? ' aria-label="'.$text.'"' : '').'>'.
                            ((!empty($icon)) ? '<span aria-hidden="true">'.$icon.'</span>' : $text) .
                    '</'.$element.'>'.
                '</li>'
            ;
        }

        /**
         *
         */
        private function linkLi($class, $href, $text) {
            return $this->elementLi('a', $href, $class, '', $text, null);
        }

        /**
         *
         */
        private function spanLi($class, $text) {
            return $this->elementLi('span', '', $class, '', $text, null);
        }
    }

}
