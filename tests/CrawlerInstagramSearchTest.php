<?php

use PHPUnit\Framework\TestCase;

require 'common.php';

/**
 * @covers CrawlerInstagramSearch
 */
final class CrawlerInstagramSearchTest extends TestCase
{
    public function testCanBeCreatedFromTwitterSearch()
    {
        $this->assertInstanceOf(
            CrawlerInstagramSearch::Class,
            new CrawlerInstagramSearch()
        );
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage keywords required for instagram search
     */
    public function testKeywordsCannotBeNull()
    {
        $crawler = new CrawlerInstagramSearch();
        $crawler->setConfig([
         'keyword_check' => ['魔兽争霸'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage keywords cannot be empty for instagram search
     */
    public function testKeywordsCannotBeEmpty()
    {
        $crawler = new CrawlerInstagramSearch();
        $crawler->setConfig([
            'keywords' => [],
            'keyword_check' => ['魔兽争霸'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage invalid page setting for instagram search
     */
    public function testPageCannotBeZero()
    {
        $crawler = new CrawlerInstagramSearch();
        $crawler->setConfig([
            'keywords' => ['DOTA2'],
            'page' => 0,
            'keyword_check' => ['魔兽争霸'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage invalid page setting for instagram search
     */
    public function testPageCannotBeNegative()
    {
        $crawler = new CrawlerInstagramSearch();
        $crawler->setConfig([
            'keywords' => ['DOTA2'],
            'page' => -1,
            'keyword_check' => ['魔兽争霸'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    public function testCanGetEnoughMessageWithPageConfig()
    {
        $crawler = new CrawlerInstagramSearch();
        $crawler->setConfig([
            'keywords' => ['exo'],
            'page' => 1,
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();

        $this->assertEquals(21,count($crawler->getMessage()));
        foreach ($crawler->getMessage() as $key => $value) {
            $this->assertArrayHasKey('item_id',$value);
            $this->assertArrayHasKey('link',$value);
            $this->assertArrayHasKey('text',$value);
            $this->assertArrayHasKey('author',$value);
            $this->assertArrayHasKey('created_at_time',$value);
        }
    }

    public function testCannotGetEnoughMessageWithVideoConfig()
    {
        $crawler = new CrawlerInstagramSearch();
        $crawler->setConfig([
            'keywords' => ['exo'],
            'page' => 1,
            'video_check' => 1
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();

        $this->assertLessThanOrEqual(21,count($crawler->getMessage()));
    }

    public function testCannotGetEnoughMessageWithKeywordcheckConfig()
    {
        $crawler = new CrawlerInstagramSearch();
        $crawler->setConfig([
            'keywords' => ['exo'],
            'page' => 1,
            'keyword_check' => ['exo'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();

        $this->assertLessThanOrEqual(21,count($crawler->getMessage()));
    }

}
