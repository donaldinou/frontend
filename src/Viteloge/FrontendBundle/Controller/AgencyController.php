<?php

namespace Viteloge\FrontendBundle\Controller {

    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\HttpFoundation\Cookie;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\Serializer\Serializer;
    use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Pagerfanta\Pagerfanta;
    use Pagerfanta\Adapter\ArrayAdapter;
    use Pagerfanta\Adapter\DoctrineORMAdapter;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Acreat\InseeBundle\Entity\InseeDepartment;
    use Acreat\InseeBundle\Entity\InseeState;
    use Viteloge\CoreBundle\Entity\Ad;
    use Viteloge\CoreBundle\Entity\Agence;
    use Viteloge\CoreBundle\Entity\QueryStats;
    use Viteloge\CoreBundle\Entity\Statistics;
    use Viteloge\CoreBundle\Entity\WebSearch;
    use Viteloge\CoreBundle\Entity\UserSearch;
    use Viteloge\CoreBundle\Component\DBAL\EnumTransactionType;
    use Viteloge\CoreBundle\Component\Enum\DistanceEnum;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

    /**
     * Note: this controller to have a short route name
     * @Route("/announcement")
     */
    class AgencyController extends Controller {

        const DESCRIPTION_LENGHT = 60;

        /**
         * view the hosted page.
         *
         *
         * @Route(
         *     "/{id}/{description}",
         *     requirements={
         *
         *     },
         *     name="viteloge_frontend_agency_view"
         * )
         * @Cache(expires="tomorrow", public=true)
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:redirect_new.html.twig")
         */
        public function viewAction(Request $request,$id) {
            $em = $this->getDoctrine()->getManager();
            $id= explode('-', $id);
            $ad = $em->getRepository('VitelogeCoreBundle:Ad')->find($id[1]);

            $session = $request->getSession();
            $ads = $session->get('resultAd');
            $search = $session->get('request');

            // Form
            $adSearch = new AdSearch();
            $adSearch->handleRequest($search);

            $form = $this->createForm('viteloge_core_adsearch', $adSearch);

            $translated = $this->get('translator');
            // SEO
            $canonicalLink = $this->get('router')->generate(
                $request->get('_route'),
                $request->get('_route_params'),
                true
            );
            $seoPage = $this->container->get('sonata.seo.page');

           $title = $ad->getcityName();
            if($ad->getTransaction() == 'V'){
               $title .= ' à vendre';
           }elseif($ad->getTransaction() == 'L'){
               $title .= ' à louer';
           }elseif($ad->getTransaction() == 'N'){
               $title .= ' neuf';
           }

           $title .= ' '.$translated->transChoice('ad.rooms.url',$ad->getRooms(), array('%count%' => $ad->getRooms()));
           $title .= ' '.$translated->transChoice('ad.bedrooms.url', $ad->getBedrooms(), array('%count%' => $ad->getBedrooms()));

           if(!empty($ad->getRooms()) || !empty($ad->getBedrooms())){
            if(!empty($ad->getSurface())){
             $title .= ' '.$ad->getSurface().' métres carrés';
            }
           }

            $filters = $this->get('twig')->getFilters();
            $callable = $filters['truncate']->getCallable();
            $description = strtolower($callable($this->get('twig'), $ad->getDescription(), self::DESCRIPTION_LENGHT));

            $seoPage
                ->setTitle($title)
                ->addMeta('name', 'robots', 'noindex, nofollow')
                ->addMeta('name', 'description', $description)
                ->addMeta('property', 'og:title', $seoPage->getTitle())
                ->addMeta('property', 'og:type', 'website')
                ->addMeta('property', 'og:url',  $canonicalLink)
                ->addMeta('property', 'og:description', $description)
                ->setLinkCanonical($canonicalLink)
            ;
            // --

            $forbiddenUA = array(
                'yakaz_bot' => 'YakazBot/1.0',
                'mitula_bot' => 'java/1.6.0_26'
            );
            $forbiddenIP = array(

            );
            $ua = $request->headers->get('User-Agent');
            $ip = $request->getClientIp();

            // log redirect
            if (!in_array($ua, $forbiddenUA) && !in_array($ip, $forbiddenIP)) {
                $now = new \DateTime('now');
                $statistics = new Statistics();
                $statistics->setIp($ip);
                $statistics->setUa($ua);
                $statistics->initFromAd($ad);

                $em = $this->getDoctrine()->getManager();
                $em->persist($statistics);
                $em->flush();
            }
            $cookies = $request->cookies;


            // on verifie si le bien est déja en favorie
            $favorie = false;
            if ($cookies->has('viteloge_favorie')){
              $info_cookies_favorie = explode('#$#', $cookies->get('viteloge_favorie')) ;
              $favorie = in_array($ad->getId(), $info_cookies_favorie);
            }

            if ($cookies->has('viteloge_photo'))
            {
                $info_cookies_photo = explode('#$#', $cookies->get('viteloge_photo')) ;
                    $j = count($info_cookies_photo);
                    if($j <= 5){
                        // si moins de 6 photo on ajoute
                       $cookie_photo = $cookies->get('viteloge_photo').'#$#'.$ad->getPhoto();
                    }else{
                        //ici on supprime le premier element du tableau et reconstruit
                        $cookie_photo = $ad->getPhoto();
                        unset($info_cookies_photo[5]);
                        foreach ($info_cookies_photo as  $value) {

                                $cookie_photo .= '#$#'.$value;

                        }

                    }
            }else{
                $cookie_photo = $ad->getPhoto();
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
            $left = $id[0]-1;
            $right = $id[0]+1;
/*
           $response->headers->clearCookie('viteloge_photo');
            $response->headers->clearCookie('viteloge_url');
*/

            // Envoie le cookie
            $response->headers->setCookie(new Cookie('viteloge_photo', $cookie_photo));
            $response->headers->setCookie(new Cookie('viteloge_url', $cookie_url));
            $response->headers->setCookie(new Cookie('viteloge_title', $cookie_title));
            //$response->send();

            $verifurl= $this->verifurl($ad->getUrl());
            if($verifurl){
            return $this->redirect($this->generateUrl('viteloge_frontend_ad_redirect', array('request'=> $request,'id'=>$ad->getId())));
            }
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
            return $this->render('VitelogeFrontendBundle:Ad:redirect_new.html.twig',array(
                'form' => $form->createView(),
                'ad' => $ad,
                'ads'=> $ads,
                'left' => $left,
                'right' => $right,
                'clef' => $id[0],
                'favorie' => $favorie,
                'csrf_token' => $csrfToken,
            ), $response);
        }

        private function verifUrl($url){
            $error=false;
            $urlhere = $url;
            $ch = curl_init();

            $options = array(
                CURLOPT_URL            => $urlhere,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_AUTOREFERER    => true,
                CURLOPT_CONNECTTIMEOUT => 120,
                CURLOPT_TIMEOUT        => 120,
                CURLOPT_MAXREDIRS      => 10,
            );
            curl_setopt_array($ch, $options);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch);
            $headers=substr($response, 0, $httpCode['header_size']);
            if(strpos($headers, 'X-Frame-Options: deny')>-1||strpos($headers, 'X-Frame-Options: SAMEORIGIN')>-1) {
                $error=true;
            }
            $httpcode= curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            return $error;
        }

        /**
         * find surtax phone.
         *
         *
         * @Route(
         *     "/phone/surtax/{id}",
         *     requirements={
         *        "id"="\d+",
         *     },
         *     name="viteloge_frontend_agency_phone"
         * )
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:fragment/btn_phone.html.twig")
         */
        function getNumSurtaxeAction($id)
        {
            if($request->isXmlHttpRequest()){
            // Clef pour l’API :
            $clef = "b28b9b89b6aea1dc6287a6d446e001a8";
            //on cherche le numero de l'agence avec son $id
            $em = $this->getDoctrine()->getManager();
            $agence = $em->getRepository('VitelogeCoreBundle:Agence')->find($id);
            if(!empty($agence)) $tel = $agence->getTel();
            $num ='';

            if(isset($tel) && !empty($tel)){
                $tel = preg_replace("([^0-9]+)","",$tel);
                $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
                $xml.=
                "<createLink><clef>".$clef."</clef><numero>".$tel."</numero><pays>FR</pays></createLink>";
                // Lien vers l’API :
                $url = "http://mer.viva-multimedia.com/xmlRequest.php?xml=".urlencode($xml);
                $ch = curl_init ($url) ;
                curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1) ;
                $res = curl_exec ($ch) ;
                curl_close ($ch);
                $array = array();
                //recupération du numéro et du code
                if (ereg("<numero>(.*)</numero>.*<code>(.*)</code>", $res, $regs))
                {

                    $tel = ((strlen($regs[1]) == "10") ? wordwrap($regs[1], 2, '.', 1) : $regs[1]);
                    $array[] = $tel ;
                    $array[] = $regs[2] ;
                }
                $num = implode('¤',$array);
                $num = rtrim($num,'¤');

            }

            return array('phone' => $num);
            }else{
             throw new \Exception("Erreur");
            }
        }

    }


}
