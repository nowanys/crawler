<?php

use PHPUnit\Framework\TestCase;

require 'common.php';

/**
 * @covers CrawlerYinyuetaiSearch
 */
final class CrawlerYinyuetaiSearchTest extends TestCase
{
    public function testCanBeCreatedFromYinyuetaiSearch()
    {
        $this->assertInstanceOf(
            CrawlerYinyuetaiSearch::class,
            new CrawlerYinyuetaiSearch()
        );
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage keywords required for yinyuetai search
     */
    public function testKeywordsCannotBeNull()
    {
        $crawler = new CrawlerYinyuetaiSearch();
        $crawler->setConfig([
         'keyword_check' => ['防弹少年团'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage keywords cannot be empty for yinyuetai search
     */
    public function testKeywordsCannotBeEmpty()
    {
        $crawler = new CrawlerYinyuetaiSearch();
        $crawler->setConfig([
            'keywords' => [],
            'keyword_check' => ['防弹少年团'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage invalid page setting for yinyuetai search
     */
    public function testPageCannotBeZero()
    {
        $crawler = new CrawlerYinyuetaiSearch();
        $crawler->setConfig([
            'keywords' => ['防弹少年团'],
            'page' => 0,
            'keyword_check' => ['魔兽争霸'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage invalid page setting for yinyuetai search
     */
    public function testPageCannotBeNegative()
    {
        $crawler = new CrawlerYinyuetaiSearch();
        $crawler->setConfig([
            'keywords' => ['防弹少年团'],
            'page' => -1,
            'keyword_check' => ['DOTA'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();
    }

    public function testCanGetEnoughMessageWithPageConfig()
    {
        $crawler = new CrawlerYinyuetaiSearch();
        $crawler->setConfig([
            'keywords' => ['防弹少年团'],
            'keyword_check' => [],
            'page' => 2,
            // 'debug' => 1,
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();

        $this->assertGreaterThanOrEqual(20,count($crawler->getMessage()));
        foreach ($crawler->getMessage() as $key => $value) {
            $this->assertArrayHasKey('id',$value);
            $this->assertArrayHasKey('duration',$value);
            $this->assertArrayHasKey('pics',$value);
            $this->assertArrayHasKey('value',$value);
            $this->assertArrayHasKey('artists',$value);
            $this->assertArrayHasKey('title',$value);
        }
    }

    public function testCannotGetEnoughMessageWithKeywordcheckConfig()
    {
        $crawler = new CrawlerYinyuetaiSearch();
        $crawler->setConfig([
            'keywords' => ['防弹少年团'],
            'page' => 2,
            'keyword_check' => ['DNA'],
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();

        $this->assertLessThanOrEqual(20,count($crawler->getMessage()));
    }

    public function testCannotGetEnoughMessageWithPublicTimecheckConfig()
    {
        $crawler = new CrawlerYinyuetaiSearch();
        $crawler->setConfig([
            'keywords' => ['防弹少年团'],
            'page' => 1,
            'public_time_check' => '2017-12-01',
        ]);
        $crawler->prepareCrawl();
        $crawler->executeCrawl();

        $this->assertLessThanOrEqual(10,count($crawler->getMessage()));
    }

}
