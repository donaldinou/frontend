<?php

namespace Viteloge\FrontendBundle\Services {

    use Doctrine\ORM\EntityManager;
    use Symfony\Component\DependencyInjection\ContainerInterface;
    use Symfony\Component\HttpFoundation\RequestStack;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Cookie;

    class cookiesFactory {

       private $em;
       private $requestStack;
       private $request;

         public function __construct(RequestStack $requestStack,EntityManager $em){

            $this->em = $em;
            $this->requestStack = $requestStack;
            $this->request = $this->requestStack->getCurrentRequest();

         }

        public function searchFav($ad){
            $cookies = $this->request->cookies;
            $favorie = false;
            if ($cookies->has('viteloge_favorie')){
              $info_cookies_favorie = explode('#$#', $cookies->get('viteloge_favorie')) ;
              $favorie = in_array($ad->getId(), $info_cookies_favorie);
            }
        }

        public function generateView($ad){
             $time =time() + (3600 * 24 * 365);
             $cookies = $this->request->cookies;
                       if(!empty($ad->getPhoto())){
                         $photo = $ad->getPhoto();
                        }else{
                          $photo = 'no-picture.jpg';
                        }

            if ($cookies->has('viteloge_photo'))
            {
                $info_cookies_photo = explode('#$#', $cookies->get('viteloge_photo')) ;
                    $j = count($info_cookies_photo);
                    if($j <= 5){
                       // si moins de 6 photo on ajoute

                         $cookie_photo = $cookies->get('viteloge_photo').'#$#'.$photo;
                    }else{
                        //ici on supprime le premier element du tableau et reconstruit
                        $cookie_photo = $photo;
                        unset($info_cookies_photo[5]);
                        foreach ($info_cookies_photo as  $value) {

                                $cookie_photo .= '#$#'.$value;

                        }

                    }
            }else{
                $cookie_photo = $photo;
            }

            if ($cookies->has('viteloge_url'))
            {
                $info_cookies_url = explode('#$#', $cookies->get('viteloge_url')) ;
                    $i = count($info_cookies_url);
                    if($i <= 5){
                        // si moins de 6 photo on ajoute
                       $cookie_url = $cookies->get('viteloge_url').'#$#'.$ad->getUrl();
                    }else{
                        //ici on supprime le premier element du tableau et reconstruit
                        $cookie_url=$ad->getUrl();
                        unset($info_cookies_url[5]);
                        foreach ($info_cookies_url as $k => $url) {
                                $cookie_url .= '#$#'.$url;
                        }
                    }
            }else{
                $cookie_url = $ad->getUrl();
            }

            $title = $ad->getAgencyName().': '.$ad->getType();
            if($ad->getTransaction() == 'V'){
                $title .= ' à vendre';
            }elseif($ad->getTransaction() == 'L'){
               $title .= ' à louer';
            }elseif($ad->getTransaction() == 'N'){
                $title .= ' neuf';
            }

            if ($cookies->has('viteloge_title'))
            {
                $info_cookies_title = explode('#$#', $cookies->get('viteloge_title')) ;
                    $i = count($info_cookies_title);
                    if($i <= 5){
                        // si moins de 6 photo on ajoute
                       $cookie_title = $cookies->get('viteloge_title').'#$#'.$title;
                    }else{
                        //ici on supprime le premier element du tableau et reconstruit
                        $cookie_title=$title;
                        unset($info_cookies_title[5]);
                        foreach ($info_cookies_title as $k => $value) {
                                $cookie_title .= '#$#'.$value;
                        }
                    }
            }else{
                $cookie_title = $title;
            }

            $response = new Response();
            $response->headers->setCookie(new Cookie('viteloge_photo', $cookie_photo, $time));
            $response->headers->setCookie(new Cookie('viteloge_url', $cookie_url, $time));
            $response->headers->setCookie(new Cookie('viteloge_title', $cookie_title, $time));
            return $response;

        }

    }

}
