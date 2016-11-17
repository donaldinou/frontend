<?php

/*
 * This file is part of the prestaSitemapPlugin package.
 * (c) David Epely <depely@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Viteloge\FrontendBundle\Services;

use Presta\SitemapBundle\DependencyInjection\Configuration;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Presta\SitemapBundle\Sitemap\DumpingUrlset;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Application\Sonata\FormatterBundle\DependencyInjection\Compiler\OverrideServiceCompilerPass;
use Presta\SitemapBundle\Service\AbstractGenerator;
use Presta\SitemapBundle\Service\Dumper as BaseDumper;

/**
 * Service for dumping sitemaps into static files
 *
 * @author Konstantin Tjuterev <kostik.lv@gmail.com>
 * @author Konstantin Myakshin <koc-dp@yandex.ru>
 */
class Dumper extends BaseDumper
{


    public function dump($targetDir, $host, $section = null, array $options = array())
    {
        $options = array_merge(array('gzip' => false), $options);

        $this->baseUrl = $host;

        // we should prepare temp folder each time, because dump may be called several times (with different sections)
        // and activate command below removes temp folder
        $this->prepareTempFolder();
        $this->populate($section);

        // if no urlset wasn't created during populating
        // it means no URLs were added to the sitemap
        if (!count($this->urlsets)) {
            $this->cleanup();

            return false;
        }

        foreach ($this->urlsets as $key => $urlset) {
       /*    $first_urlset = explode('_', basename($urlset->getLoc()));
           if($first_urlset[3] == '0.xml'){
            $subSitemap[$first_urlset[1]] = $first_urlset[0].'_'.$first_urlset[1].'.xml';
            $filenames[] = $subSitemap[$first_urlset[1]];
            }*/
            $urlset->save($this->tmpFolder, $options['gzip']);
            $filenames[] = basename($urlset->getLoc());
        }

        if (null !== $section) {
            // Load current SitemapIndex file and add all sitemaps except those,
            // matching section currently being regenerated to root
            foreach ($this->loadCurrentSitemapIndex($targetDir . '/' . $this->sitemapFilePrefix . '.xml') as $key => $urlset) {
                // cut possible _X, to compare base section name
                $baseKey = preg_replace('/(.*?)(_\d+)?/', '\1', $key);
                if ($baseKey !== $section) {
                    // we add them to root only, if we add them to $this->urlset
                    // deleteExistingSitemaps() will delete matching files, which we don't want
                    $this->getRoot()->addSitemap($urlset);
                }
            }
        }

    /*  if($section == 'cities'){
           file_put_contents($this->tmpFolder . '/' . 'sitemap_'.$section . '.xml', $this->getRoot()->toXml());
           $filenames[] = 'sitemap_'.$section . '.xml';
           $filenames[] = $this->sitemapFilePrefix . '.xml';
         //  $this->getRoot()->addSitemap($urlset);
        }else{
          file_put_contents($this->tmpFolder . '/' . $this->sitemapFilePrefix . '.xml', $this->getRoot()->toXml());
          $filenames[] = $this->sitemapFilePrefix . '.xml';
        }*/
      //
       file_put_contents($this->tmpFolder . '/' . $this->sitemapFilePrefix . '.xml', $this->getRoot()->toXml());
        $filenames[] = $this->sitemapFilePrefix . '.xml';

    //   $filenames[] = 'sitemap_'.$section . '.xml';
        // if we came to this point - we can activate new files
        // if we fail on exception eariler - old files will stay making Google happy
        $this->activate($targetDir);

        return $filenames;
    }



}
