<?php

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface;
use Michelf\MarkdownInterface;

class MarkdownHelper
{
    private $cache;
    private $markdown;

    public function __construct(AdapterInterface $cache, MarkdownInterface $markdown)
    {
        $this->cache = $cache;
        $this->markdown = $markdown;

    }

    public function parse($source)
    {

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