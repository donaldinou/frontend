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
    use Viteloge\CoreBundle\Entity\Infos;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

    /**
     * Note: this controller to have a short route name
     * @Route("/announcement")
     */
    class AgencyController extends Controller {

        const DESCRIPTION_LENGHT = 150;

        /**
         * view the hosted page.No cache for this page
         *
         *
         * @Route(
         *     "/{id}/{description}",
         *     requirements={
         *
         *     },
         *     name="viteloge_frontend_agency_view"
         * )
         * @Route(
         *     "/favourite/{id}/{description}",
         *     requirements={
         *
         *     },
         *     name="viteloge_frontend_favourite_view"
         * )
         * @Route(
         *     "/home/{id}/{description}",
         *     requirements={
         *
         *     },
         *     name="viteloge_frontend_agency_home"
         * )
         * @Route(
         *     "/lastview/{id}/{description}",
         *     requirements={
         *
         *     },
         *     name="viteloge_frontend_agency_lastview"
         * )
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Ad:redirect_new.html.twig")
         */
        public function viewAction(Request $request,$id, $description) {
            $routeName = $request->get('_route');
            $infos = explode('-', $description);
            $em = $this->getDoctrine()->getManager();
            $id= explode('-', $id);
            $ad = $em->getRepository('VitelogeCoreBundle:Ad')->find($id[1]);

            $session = $request->getSession();
            if($routeName == 'viteloge_frontend_agency_lastview'){
               $ads = $session->get('resultView');
            }else{
               $ads = $session->get('resultAd');
            }

            $veriftotal = $session->get('totalResultVente');

            if($request->query->get('transaction') == 'V' && !is_null($veriftotal)){
              $total = $session->get('totalResultVente');

            }else{
              $total = $session->get('totalResult');
              $a = ($session->get('currentPage')-1) * 25;
              $b = $total - ($a);
              if($b > 25){
                $total = 25;
              }else{
                $total = $b;
              }

            }

            $search = $session->get('request');
            //si on atteind le nbs max de resultat en session on relance la recherche
            // Form
            $adSearch = new AdSearch();
            $adSearch->handleRequest($search);

            if(!isset($ad)){
               $options = array(
                'sort' => array(
                    'isCapital' => array( 'order' => 'desc' ),
                    'population' => array( 'order' => 'desc' )
                )
            );

            $search = $infos[1];
            $search = \Elastica\Util::escapeTerm($search);
            $index = $this->container->get('fos_elastica.finder.viteloge.inseeCity');
            $searchQuery = new \Elastica\Query\QueryString();
            $searchQuery->setParam('query', $search);
            $cities = $index->find($searchQuery, $options);
                        return $this->redirectToRoute(
                            'viteloge_frontend_glossary_showcity',
                            array('name' => $cities[0]->getName(),
                                  'id' => $cities[0]->getId()
                              ));

            }
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);

            $translated = $this->get('translator');
            // SEO
            $rewriteParam = $request->get('_route_params');

              $rewriteParam['id'] = '0-'.$ad->getId();

            $canonicalLink = $this->get('router')->generate(
                $request->get('_route'),
                $rewriteParam,
                true
            );


            $seoPage = $this->container->get('sonata.seo.page');
            $helper = $this->container->get('viteloge_frontend.ad_helper');

            $title = $helper->titlify($ad,true);

            $filters = $this->get('twig')->getFilters();
            $callable = $filters['truncate']->getCallable();
            $description = strtolower($callable($this->get('twig'), $ad->getDescription(), self::DESCRIPTION_LENGHT));

            $seoPage
                ->setTitle($title)
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
                $now = new \DateTime('now',new \DateTimeZone('Europe/Paris'));
                $statistics = new Statistics();
                $statistics->setIp($ip);
                $statistics->setUa($ua);
                $statistics->initFromAd($ad);

                $em = $this->getDoctrine()->getManager();
                $em->persist($statistics);
                $em->flush();
            }

            $favorie = $this->get('viteloge_frontend_generate.cookies')->searchFav($ad);
            $response = $this->get('viteloge_frontend_generate.cookies')->generateView($ad);

            $left = $id[0]-1;
            $right = $id[0]+1;

            $verifurl= $this->verifurl($ad->getUrl());

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
                'redirect' => $verifurl,
                'total' => $total
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
            if(strpos($headers, 'X-Frame-Options: deny')>-1 || strpos($headers, 'X-Frame-Options: SAMEORIGIN')>-1) {
                $error=true;
            }
            $redirectUrl = array(
                'century' => 'https://www.century21.fr',
                'century21' => 'http://www.century21.fr',
                'paruvendu' => 'http://www.paruvendu.fr/',
                'paruvendus' => 'https://www.paruvendu.fr/',
            );
            $verifurl = explode('://', $url);
            $baseurl = explode('/', $verifurl[1]);
            $newurl = $verifurl[0].'://'.$baseurl[0];

            if(in_array($newurl, $redirectUrl)){
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
         * @Method({"POST"})
         * @ParamConverter("ad", class="VitelogeCoreBundle:Ad", options={"id" = "id"})
         * @Template("VitelogeFrontendBundle:Ad:fragment/btn_phone.html.twig")
         */
        function getNumSurtaxeAction(Request $request,Ad $ad)
        {

            if($request->isXmlHttpRequest()){
            // Clef pour l’API :
            $clef = "b28b9b89b6aea1dc6287a6d446e001a8";
            //on cherche le numero de l'agence avec son $id
            $em = $this->getDoctrine()->getManager();
            $agence = $em->getRepository('VitelogeCoreBundle:Agence')->find($ad->getAgencyId());




            if(!empty($agence)) $tel = $agence->getTel();
            $num ='Pas de Numéro';
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
                        $contact = new Infos();
                        $contact->setIp($ip);
                        $contact->setUa($ua);

                        $contact->initFromAd($ad);


            if(isset($tel) && !empty($tel)){
              $contact->setGenre('dempandephone');
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

            }else{
              $contact->setGenre('phoneempty');
            }

                $em = $this->getDoctrine()->getManager();
                $em->persist($contact);
                $em->flush();
            }
            $cout = '1,34€/appel.0,34€/mn';
            $response = new JsonResponse();
            return $response->setData(array('phone' => $num, 'cout' => $cout, 'id' => $ad->getId()));
            //return array('phone' => $num);
            }else{
             throw new \Exception("Erreur");
            }
        }

        /**
         * call surtax phone.
         *
         *
         * @Route(
         *     "/phone/call/{id}",
         *     requirements={
         *        "id"="\d+",
         *     },
         *     name="viteloge_frontend_agency_call"
         * )
         * @Method({"POST"})
         * @ParamConverter("ad", class="VitelogeCoreBundle:Ad", options={"id" = "id"})
         */
        function callPhoneAction(Request $request,Ad $ad)
        {
          if($request->isXmlHttpRequest()){
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
                        $contact = new Infos();
                        $contact->setIp($ip);
                        $contact->setUa($ua);
                        $contact->setGenre('appelle');
                        $contact->initFromAd($ad);
                        $em = $this->getDoctrine()->getManager();
                        $em->persist($contact);
                        $em->flush();
                    }
                 $response = new JsonResponse();
            return $response->setData(array());
            }else{
             throw new \Exception("Erreur");
            }
        }

         /**
         * Display the last ad (Used in homepage)
         * Ajax call so we could have shared public cache
         *
         * @Route(
         *     "/latest/{limit}",
         *     requirements={
         *         "limit"="\d+"
         *     },
         *     defaults={
         *         "limit" = "5"
         *     },
         *     name="viteloge_frontend_agency_latest_limited"
         * )
         * @Route(
         *      "/latest/",
         *      requirements={
         *         "limit"="\d+"
         *      },
         *      defaults={
         *         "limit" = "5"
         *      },
         *      name="viteloge_frontend_agency_latest"
         * )
         * @Method({"GET"})
         * @Template("VitelogeFrontendBundle:Statistics:latest.html.twig")
         */
         public function latestViewAction(Request $request, $limit) {
            $em = $this->getDoctrine()->getManager();
            $ads = $em->getRepository('VitelogeCoreBundle:Statistics')->findBy(array(), array('date' => 'DESC'),10);

            $adSearch = new AdSearch();
            $adSearch->handleRequest($request);
            $form = $this->createForm('viteloge_core_adsearch', $adSearch);
            $elasticaManager = $this->container->get('fos_elastica.manager');
            $repository = $elasticaManager->getRepository('VitelogeCoreBundle:Ad');
            $pagination = $repository->searchPaginated($form->getData());
            // Save session
            $session = $request->getSession();
            $session->set('totalResult',$pagination->getNbResults());
            $session->set('resultAd',$pagination->getCurrentPageResults());
            $session->remove('request');
            return array(
                'ads' => $ads
            );
        }
       /* public function latestViewAction(Request $request, $limit) {
            $em = $this->getDoctrine()->getManager();
            $ads = $em->getRepository('VitelogeCoreBundle:Infos')->findBy(array('genre'=> 'search'), array('date' => 'DESC'),$limit);
            return array(
                'ads' => $ads
            );
        }*/

        /**
         * ajax last search (Home).
         *
         *
         * @Route(
         *     "/last/view",
         *     name="viteloge_frontend_agency_last_view"
         * )
         * @Method({"POST"})
         * @Template("VitelogeFrontendBundle:Statistics:render/ajax_latest.html.twig")
         */
        function lastSearchAction(Request $request)
        {
          if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $ads = $em->getRepository('VitelogeCoreBundle:Statistics')->findBy(array(), array('date' => 'DESC'),10);
            return array(
                'ads' => $ads
            );
            }else{
             throw new \Exception("Erreur");
            }
        }
       /* function lastSearchAction(Request $request)
        {
          if($request->isXmlHttpRequest()){
            $em = $this->getDoctrine()->getManager();
            $ads = $em->getRepository('VitelogeCoreBundle:Infos')->findBy(array('genre'=> 'search'), array('date' => 'DESC'),5);
            return array(
                'ads' => $ads
            );
            }else{
             throw new \Exception("Erreur");
            }
        }*/


    }


}
