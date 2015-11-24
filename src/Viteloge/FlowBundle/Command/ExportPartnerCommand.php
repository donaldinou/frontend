<?php
namespace Viteloge\FlowBundle\Command {

    use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Helper\ProgressBar;
    use Symfony\Component\Filesystem\Filesystem;
    use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Viteloge\CoreBundle\Entity\CityData;

    /**
     *
     */
    class ExportPartnerCommand extends ContainerAwareCommand {

        /**
         *
         */
        protected function configure() {
            $this
                ->setName('viteloge:export:partner')
                ->setDescription('Import description for city data')
                ->addArgument(
                    'partners',
                    InputArgument::IS_ARRAY,
                    'Wich partner (separate multiple names with a space)?'
                )
                /*->addArgument(
                    'folder',
                    InputArgument::OPTIONAL,
                    'Wich folder?'
                )
                ->addOption(
                    'force',
                    null,
                    InputOption::VALUE_NONE,
                    'If set, force update will be perform'
                )*/
            ;
        }

        /**
         *
         */
        protected function execute(InputInterface $input, OutputInterface $output) {
            //$logger = $this->getContainer()->get('logger');
            //$translator = $this->getContainer()->get('translator');

            ini_set('memory_limit', '8192M');
            set_time_limit(360000);

            $entityManager = $this->getContainer()->get('doctrine')->getEntityManager();
            $adRepository = $entityManager->getRepository('VitelogeCoreBundle:Ad');

            // find data
            $output->writeln('<info>Retrieve exportable data</info>');
            $data = $adRepository->findByExportable();
            $iterator_count = count($data);
            $output->writeln('There are '.$iterator_count.' ads to export');

            $names = $input->getArgument('partners');
            $progress = new ProgressBar($output, $iterator_count);
            foreach ($names as $key => $name) {
                $output->writeln('Try to generate '.$name.' exportable flow');
                $progress->start();
                switch ($name) {
                    case 'trovit':
                        $filename = 'trovit_feeds.xml';
                        $object = $this->generateTrovitExport($data, $progress);
                        break;
                    case 'yakaz':
                        $filename = 'yakaz_feed.xml';
                        $object = $this->generateYakazExport($data, $progress);
                        break;
                    case 'mitula':
                        $filename = 'mitula.xml';
                        $object = $this->generateMitulaExport($data, $progress);
                        break;
                    default:
                        $progress->clear();
                        $output->writeln('<error>Bad partner: '.$name.'</error>');
                        $progress->display();
                        break;
                }
                $progress->finish();
                $output->writeln('');

                try {
                    $output->writeln('<info>Prepare to serialize</info>');
                    $xml = $object->serialize('xml');
                } catch (\Exception $e) {
                    $output->writeln('<error>'.$e->getMessage().'</error>');
                    $xml = '<error/>';
                }

                $file = $this->getContainer()->get('kernel')->getRootDir().'/../web/'.$filename;
                $output->writeln('<info>Generate '.$file.'</info>');
                $fs = new Filesystem();
                $fs->dumpFile($file, $xml);
            }
        }

        /**
         *
         */
        protected function generateTitle($row) {
            $title = $row->getType();
            $title .= ($row->getTransaction() == 'L') ? ' à louer ' : ' à vendre ';
            $title .= $row->getInseeCity()->getName().' '.$row->getPostalCode();
            return $title;
        }

        /**
         *
         */
        protected function generateMitulaExport($data, ProgressBar $progress=null) {
            $router = $this->getContainer()->get('router');
            $domain = $this->getContainer()->getParameter('media_domain');

            $root = new \Acreat\MitulaBundle\Component\Mitula();
            foreach ($data as $key => $row) {
                $title = $this->generateTitle($row);

                $ad = new \Acreat\MitulaBundle\Component\Element\Ad();
                $ad->id = $row->getId();
                $ad->url = $router->generate('viteloge_frontend_ad_redirect', array('id' => $row->getId()), true);
                $ad->title = $title;
                $ad->type = \Viteloge\FlowBundle\Enum\Transaction::getMitulaConstant($row->getTransaction());
                $ad->content = $row->getDescription();
                $ad->price = $row->getPrice();
                $ad->floorArea = new \Acreat\MitulaBundle\Component\Attribute\FloorArea($row->getSurface());

                // manage photo
                $photo = $row->getPhoto();
                if (!empty($photo)) {
                    $picture = new \Acreat\MitulaBundle\Component\Element\Picture();
                    $picture->url = 'http://'.$domain.'/'.$photo;
                    $picture->title = $title;
                    $ad->pictures = new \Acreat\MitulaBundle\Component\Collection\Pictures();
                    $ad->pictures->append($picture);
                }

                // manage property type
                if (in_array($ad->type, array(\Acreat\MitulaBundle\Enum\AdType::SALE, \Acreat\MitulaBundle\Enum\AdType::RENT))) {
                    $ad->propertyType = $row->getType();
                }

                // load some vars
                $districtId = $row->getDistrictId();
                $inseeCity = $row->getInseeCity();
                $inseeDepartment = (!empty($inseeCity)) ? $inseeCity->getInseeDepartment() : null;

                $ad->rooms = $row->getRooms();
                $ad->cityArea = (!empty($districtId)) ? $row->getCityName().', '.$districtId : null;
                $ad->city = $row->getCityName();
                $ad->region = (!empty($inseeDepartment)) ? $inseeDepartment->getName() : '';
                $ad->postcode = $row->getPostalCode();

                // is new?
                if ($row->getTransaction() == 'N') {
                    $ad->isNew = true;
                }

                $root->ads->append($ad);

                if (!empty($progress)) {
                    $progress->advance();
                }
            }

            return $root;
        }

        /**
         *
         */
        protected function generateTrovitExport($data, ProgressBar $progress=null) {
            $router = $this->getContainer()->get('router');
            $domain = $this->getContainer()->getParameter('media_domain');

            $root = new \Acreat\TrovitBundle\Component\Root();
            foreach ($data as $key => $row) {
                $title = $this->generateTitle($row);

                $ad = new \Acreat\TrovitBundle\Component\Ad();
                $ad->id = $row->getId();
                $ad->url = $router->generate('viteloge_frontend_ad_redirect', array('id' => $row->getId()), true);
                $ad->title = $title;
                $ad->type = \Viteloge\FlowBundle\Enum\Transaction::getTrovitConstant($row->getTransaction());
                $ad->content = $row->getDescription();
                $ad->price = $row->getPrice();

                // manage photo
                $photo = $row->getPhoto();
                if (!empty($photo)) {
                    $picture = new \Acreat\TrovitBundle\Component\Picture();
                    $picture->url = 'http://'.$domain.'/'.$photo;
                    $picture->title = $title;
                    $ad->pictures = new \Acreat\TrovitBundle\Component\Pictures();
                    $ad->pictures->append($picture);
                }

                // manage property type
                if (in_array($ad->type, array(\Acreat\TrovitBundle\Enum\AdType::FOR_SALE, \Acreat\TrovitBundle\Enum\AdType::FOR_RENT))) {
                    $ad->propertyType = $row->getType();
                }

                // load some vars
                $districtId = $row->getDistrictId();
                $inseeCity = $row->getInseeCity();
                $inseeDepartment = (!empty($inseeCity)) ? $inseeCity->getInseeDepartment() : null;

                // next optional fields
                //$ad->address = $row[''];
                //$ad->floorNumber = $row[''];
                $ad->cityArea = (!empty($districtId)) ? $row->getCityName().', '.$districtId : null;
                $ad->city = $row->getCityName();
                $ad->region = (!empty($inseeDepartment)) ? $inseeDepartment->getName() : '';
                $ad->postcode = $row->getPostalCode();
                //$ad->latitude = $row[''];
                //$ad->longitude = $row[''];
                //$ad->orientation = $row[''];
                //$ad->agency = $row['agence'];
                //$ad->mlsDatabase = $row[''];
                //$ad->floorArea = $row[''];
                //$ad->plotArea = $row[''];
                //$ad->rooms = $row['nbpiece'];
                //$ad->bathrooms = $row[''];
                //$ad->condition = $row[''];
                //$ad->year = $row[''];
                //$ad->virtualTour = $row[''];
                //$ad->ecoScore = $row[''];
                //$ad->byOwner = $row[''];
                //$ad->isRentToOwn = $row[''];
                //$ad->parking = $row[''];
                //$ad->foreclosure = $row[''];
                //$ad->isFinished = $row[''];

                // is new?
                if ($row->getTransaction() == 'N') {
                    $ad->isNew = true;
                }

                $root->ads->append($ad);

                if (!empty($progress)) {
                    $progress->advance();
                }
            }

            return $root;
        }

        /**
         *
         */
        protected function generateYakazExport($data, ProgressBar $progress=null) {
            $router = $this->getContainer()->get('router');
            $domain = $this->getContainer()->getParameter('media_domain');

            $root = new \Acreat\YakazBundle\Component\Yakaz();
            $root->version = '1.0';
            $root->housings = new \Acreat\YakazBundle\Component\Collection\Housings();
            foreach ($data as $key => $row) {
                $title = $this->generateTitle($row);

                // required fields
                $ad = new \Acreat\YakazBundle\Component\Element\Ad\Housing();
                $ad->category = \Viteloge\FlowBundle\Enum\Transaction::getYakazConstant($row->getTransaction());
                $ad->what->title = $title;
                $ad->what->url = $router->generate('viteloge_frontend_ad_redirect', array('id' => $row->getId()), true);
                $ad->what->description = $row->getDescription();
                $ad->what->price = new \Acreat\YakazBundle\Component\Attribute\Price($row->getPrice());

                // manage photo
                $photo = $row->getPhoto();
                if (!empty($photo)) {
                    $picture = new \Acreat\YakazBundle\Component\Element\Picture();
                    $picture->value = 'http://'.$domain.'/'.$photo;
                    $ad->what->pictures = new \Acreat\YakazBundle\Component\Collection\Pictures();
                    $ad->what->pictures->append($picture);
                }

                //$ad->what->rooms = $row['nbpiece'];
                //$ad->what->bedrooms = $row[''];
                //$ad->what->bathrooms = $row[''];
                //$ad->what->surface = $row[''];

                // load some vars
                $districtId = $row->getDistrictId();
                $inseeCity = $row->getInseeCity();
                $inseeDepartment = (!empty($inseeCity)) ? $inseeCity->getInseeDepartment() : null;

                // where
                //$ad->where->streetAddress = $row[''];
                $ad->where->cityName = $row->getCityName();
                $ad->where->regionName = (!empty($inseeDepartment)) ? $inseeDepartment->getName() : '';
                $ad->where->zipCode = $row->getPostalCode();
                $ad->where->country = 'FR';

                $root->housings->append($ad);

                if (!empty($progress)) {
                    $progress->advance();
                }
            }

            return $root;
        }

    }
}
