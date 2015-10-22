<?php
namespace Viteloge\CoreBundle\Command {

    use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Helper\ProgressBar;
    use Acreat\InseeBundle\Entity\InseeCity;
    use Viteloge\CoreBundle\Entity\CityData;

    /**
     *
     */
    class ImportDescriptionCommand extends ContainerAwareCommand {

        /**
         *
         */
        protected function configure() {
            $this
                ->setName('viteloge:import:description')
                ->setDescription('Import description for city data')
                /*->addArgument(
                    'language',
                    InputArgument::OPTIONAL,
                    'Wich language?'
                )
                ->addArgument(
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
            $total = 0;
            $created = 0;
            $updated = 0;
            $entityManager = $this->getContainer()->get('doctrine')->getEntityManager();
            $inseeCityRepository = $entityManager->getRepository('AcreatInseeBundle:InseeCity');
            $cityDataRepository = $entityManager->getRepository('VitelogeCoreBundle:CityData');
            try {
                $dir = new \DirectoryIterator(__DIR__.'/../Resources/descriptions/'); //RecursiveDirectoryIterator
            } catch( \UnexpectedValueException $e ) {
                $dir = array();
            } catch( \RuntimeException $e ) {
                $dir = array();
            }
            $iterator_count = iterator_count($dir);
            $output->writeln('<info>Check for '.$iterator_count.' files</info>');
            $progress = new ProgressBar($output, $iterator_count);
            $progress->start();
            foreach ($dir as $fileinfo) {
                $progress->advance();
                if (!$fileinfo->isDot() && !$fileinfo->isDir() && $fileinfo->isReadable() && $fileinfo->valid() && $fileinfo->getExtension() == 'html') {
                    $inseeCityId = $fileinfo->getBasename('.'.$fileinfo->getExtension());
                    $inseeCity = $inseeCityRepository->findOneById($inseeCityId);
                    if ($inseeCity instanceof InseeCity) {
                        try {
                            $fileObject = $fileinfo->openFile();
                            $fileObject->rewind();
                            $contents = $fileObject->fread($fileObject->getSize());
                        } catch( \RuntimeException $e ) {

                        }
                        if (!empty($contents)) {
                            $total++;
                            $cityData = $cityDataRepository->findOneByInseeCity($inseeCity);
                            if (!$cityData instanceof CityData) {
                                $created++;
                                $cityData = new CityData();
                            } else {
                                $updated++;
                                $cityData->setUpdatedAt(new \DateTime());
                            }
                            $cityData->setInseeCity($inseeCity);
                            $cityData->setDescription($contents);
                            $entityManager->persist($cityData);
                        }
                    } else {
                        $progress->clear();
                        $output->writeln('<warning>InseeCity '.$inseeCityId.' does not exist in database</warning>');
                        $progress->display();
                    }
                }
            }
            $progress->finish();
            $output->writeln('');
            $output->writeln('<info>Prepare to save :'.$total.' descriptions</info>');
            $entityManager->flush();
            $output->writeln('<info>Created :'.$created.'</info>');
            $output->writeln('<info>Updated :'.$updated.'</info>');
            $output->writeln('<info>End</info>');
        }
    }
}
