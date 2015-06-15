<?php

namespace Viteloge\FrontendBundle\EventListener {

    use Doctrine\ORM\Event\LifecycleEventArgs;
    use Symfony\Component\EventDispatcher\Event;
    use FOS\ElasticaBundle\Manager\RepositoryManagerInterface as RepositoryManager;
    use Viteloge\CoreBundle\Entity\WebSearch;
    use Viteloge\CoreBundle\Entity\UserSearch;
    use Viteloge\CoreBundle\SearchEntity\Ad as AdSearch;

    /**
     *
     */
    class WebSearchListener {

        /**
         *
         */
        protected $elasticaManager;

        /**
         *
         */
        public function __construct(RepositoryManager $elasticaManager) {
            $this->elasticaManager = $elasticaManager;
        }

        public function prePersist(LifecycleEventArgs $args) {
            $webSearch = $args->getEntity();
            if ($webSearch instanceof WebSearch) {
                $deletedAt = $webSearch->getDeletedAt();
                $totalMatches = $webSearch->getTotalMatches();
                $userSearch = $webSearch->getUserSearch();
                if (!$totalMatches && !$deletedAt && $userSearch instanceof UserSearch) {
                    $adSearch = new AdSearch();
                    $adSearch->setTransaction($userSearch->getTransaction());
                    $adSearch->setWhat($userSearch->getType());
                    $adSearch->setRooms($userSearch->getRooms());
                    $adSearch->setMinPrice($userSearch->getBudgetMin());
                    $adSearch->setMaxPrice($userSearch->getBudgetMax());
                    $adSearch->setRadius($userSearch->getRadius());
                    $adSearch->setKeywords($userSearch->getKeywords());
                    if ($userSearch->getInseeCity() instanceof InseeCity) {
                        $adSearch->setwhere($userSearch->getInseeCity()->getId());
                    }
                    $repository = $this->elasticaManager->getRepository('VitelogeCoreBundle:Ad');
                    $ads = $repository->searchPaginated($adSearch);
                    $count = $ads->getNbResults();
                    if ($count) {
                        $webSearch->setTotalMatches($count);
                    }
                }
            }
        }

    }

}
