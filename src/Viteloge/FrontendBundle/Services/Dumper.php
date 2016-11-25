<?php

/*
 * This file is part of the prestaSitemapPlugin package.
 * (c) David Epely <depely@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Viteloge\FrontendBundle\Services;

use Presta\SitemapBundle\Sitemap\Sitemapindex;
use Presta\SitemapBundle\Service\Dumper as BaseDumper;
use Presta\SitemapBundle\Sitemap\DumpingUrlset;
/**
 * Service for dumping sitemaps into static files
 */
class Dumper extends BaseDumper {

    const SUB_SECTION_KEYWORK = '_part';

    /**
     * @var Array<Sitemap\Sitemapindex>
     */
    protected $indexes = array();

    /**
     *
     */
    protected function getIndex($name) {
        if (empty($this->indexes[$name])) {
            $this->indexes[$name] = new Sitemapindex();
            $urlset = $this->newUrlset($name);
            $this->getRoot()->addSitemap($urlset);
        }
        return $this->indexes[$name];
    }

    /**
     *
     */
    protected function getHeadSection($section, $default='') {
        if (empty($default)) {
            switch ($section) {
                case 'city_keyword_part_':
                case 'city_statistic_part_':
                case 'city_glossary_part_':
                    $default = 'cities';
                    break;
                case 'keyword_ad_part_':
                    $default = 'queries';
                    break;
                case 'agency_ad_part_':
                    $default = 'ads';
                    break;
                default:
                    $default = $section;
                    break;
            }
            if ($section === $default) {
                if (strpos($default, $this->sitemapFilePrefix.'.') !== false) {
                    $default = substr($default, strlen($this->sitemapFilePrefix.'.'));
                }
                if (strpos($default, static::SUB_SECTION_KEYWORK) !== false) {
                    $default = substr($default, 0, strpos($default, static::SUB_SECTION_KEYWORK));
                }
            }
        }
        return $default;
    }

    /**
     *
     */
    public function dump($targetDir, $host, $section = null, array $options = array()) {
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

        // reset root Sitemap
        $this->root = new Sitemapindex();

        foreach ($this->urlsets as $key => $urlset) {
            $filename = basename($urlset->getLoc());
            $tmpFolder = $this->tmpFolder;
            $sectionHead = $this->getHeadSection($filename, $section);

            if($sectionHead == "default.xml" || $sectionHead == "default"){
             $sectionHead = 'default_index';
            }
            if ($sectionHead !== $filename ) {
                $index = $this->getIndex($sectionHead);
                $index->addSitemap($urlset);
                $filenames[] = $this->sitemapFilePrefix.'.'.$sectionHead.'.xml';
                $urlset->save($tmpFolder, $options['gzip']);
                unset($this->urlsets[$key]);
            }
            else {
                $urlset->save($tmpFolder, $options['gzip']);
            }
            $filenames[] = $filename;
        }

        // save index
        foreach ($this->indexes as $name => $index) {
            file_put_contents($this->tmpFolder . DIRECTORY_SEPARATOR . $this->sitemapFilePrefix . '.'.$name.'.xml', $index->toXml());
        }

         if (null !== $section) {
            // Load current SitemapIndex file and add all sitemaps except those,
            // matching section currently being regenerated to root
            foreach ($this->loadCurrentSitemapIndex($targetDir . DIRECTORY_SEPARATOR . $this->sitemapFilePrefix . '.xml') as $key => $urlset) {
                // cut possible _X, to compare base section name
                $baseKey = preg_replace('/(.*?)(_\d+)?/', '\1', $key);
                if ($baseKey !== $section) {
                    // we add them to root only, if we add them to $this->urlset
                    // deleteExistingSitemaps() will delete matching files, which we don't want
                    $this->getRoot()->addSitemap($urlset);
                }
            }
        }

        file_put_contents($this->tmpFolder . '/' . $this->sitemapFilePrefix . '.xml', $this->getRoot()->toXml());
        $filenames[] = $this->sitemapFilePrefix . '.xml';

        // if we came to this point - we can activate new files
        // if we fail on exception eariler - old files will stay making Google happy
        $this->activate($targetDir);

        return $filenames;
    }

    /**
     * Factory method for creating Urlset objects
     *
     * @param string $name
     *
     * @param \DateTime $lastmod
     *
     * @return DumpingUrlset
     */
    protected function newUrlset($name, \DateTime $lastmod = null) {
        return new DumpingUrlset($this->baseUrl . $this->sitemapFilePrefix . '.' . $name . '.xml', $lastmod);
    }


}
