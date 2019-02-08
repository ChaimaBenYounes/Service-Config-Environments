<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Psr\Log\LoggerInterface;
use Michelf\MarkdownInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;
    private $logger;

    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;
        $this->logger = $logger;
    }

    public function parse($source)
    {

        if (stripos($source, 'bacon') !== false) {
            $this->logger->info('They are talking about bacon again!');
        }
        //We need to pass this a cache key getItem('key').
        //Use markdown_ and then md5($articleContent)
        //it just creates a caheIteam Object in memory that can help us fetch and save to the cache.
        $item = $this->cache->getItem('markdown_'.md5($source));

        //to check if this key is not already cached, use if (!$item->isHit()):
        if (!$item->isHit()) {

            //We need to put the item into cache
            $item->set($this->markdown->transform($source));
            $this->cache->save($item);
        }


        // get() to fetch the value from the cache
        return $item->get();
    }

}