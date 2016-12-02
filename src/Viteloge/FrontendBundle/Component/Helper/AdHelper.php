<?php

namespace Viteloge\FrontendBundle\Component\Helper {

    use Symfony\Component\Translation\TranslatorInterface;
    use Behat\Transliterator\Transliterator;
    use Viteloge\CoreBundle\Entity\Ad;

    /**
     *
     */
    class AdHelper {

        /**
         *
         */
        protected $translator;

        /**
         *
         */
        public function __construct(TranslatorInterface $translator) {
            $this->translator = $translator;
        }

        public function titlify(Ad $ad,$reverse = null) {
            $title = $ad->getType();
            if($reverse){
             $title = trim($title) . ' ' . $this->translator->trans('ad.slug.transaction.'.$ad->getTransaction());
            }
            $title = trim($title) . ' ' .$ad->getCityName();
            $title = trim($title) . ' ' . $this->translator->transChoice('ad.rooms.url',$ad->getRooms(), array('%count%' => $ad->getRooms())).' ';
            $title = trim($title) . ' ' . $this->translator->transChoice('ad.bedrooms.url', $ad->getBedrooms(), array('%count%' => $ad->getBedrooms())).' ';
            $title = trim($title) . ' ' . $this->translator->transChoice('ad.surface.url', $ad->getSurface(), array('%count%' => $ad->getSurface())).' ';
            if($reverse == false || is_null($reverse)){
             $title = trim($title) . ' ' . $this->translator->trans('ad.slug.transaction.'.$ad->getTransaction());
            }
            $title = trim($title) . ' ' . $ad->getAgencyName();
            return $title;
        }

        /**
         * @todo: Use Gedmo Translatable and Sluggable (https://github.com/Atlantic18/DoctrineExtensions/blob/master/doc/sluggable.md)
         */
        public function slugigy(Ad $ad) {
            $slug = $this->titlify($ad);
            return Transliterator::urlize($slug);
        }

    }

}
