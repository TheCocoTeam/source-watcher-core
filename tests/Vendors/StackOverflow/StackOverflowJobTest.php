<?php declare( strict_types=1 );

namespace Coco\SourceWatcher\Tests\Vendors\StackOverflow;

use Coco\SourceWatcher\Vendors\StackOverflow\StackOverflowJob;
use PHPUnit\Framework\TestCase;

/**
 * Class StackOverflowJobTest
 *
 * @package Coco\SourceWatcher\Tests\Vendors\StackOverflow
 */
class StackOverflowJobTest extends TestCase
{
    public string $jobId;
    public string $resultId;
    public string $previewUrl;
    public string $logo;
    public string $title;
    public string $company;
    public string $location;

    public function setUp () : void
    {
        $this->jobId = "123456";
        $this->resultId = "123456";
        $this->previewUrl = "https://stackoverflow.com/jobs/123456/some-developer-role";
        $this->logo = "https://some-website.com/assets/some-logo.svg";
        $this->title = "Some Developer Role";
        $this->company = "Acme Corporation";
        $this->location = "Saint Denis";
    }

    public function testSetGetJobId () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenJobId = $this->jobId;
        $expectedJobId = $this->jobId;

        $stackOverflowJob->setJobId( $givenJobId );
        $this->assertNotNull( $stackOverflowJob->getJobId() );
        $this->assertEquals( $expectedJobId, $stackOverflowJob->getJobId() );

        $stackOverflowJob->setJobId( null );
        $this->assertNull( $stackOverflowJob->getJobId() );
    }

    public function testSetGetResultId () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenResultId = $this->resultId;
        $expectedResultId = $this->resultId;

        $stackOverflowJob->setResultId( $givenResultId );
        $this->assertNotNull( $stackOverflowJob->getResultId() );
        $this->assertEquals( $expectedResultId, $stackOverflowJob->getResultId() );

        $stackOverflowJob->setResultId( null );
        $this->assertNull( $stackOverflowJob->getResultId() );
    }

    public function testSetGetPreviewURL () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenPreviewUrl = $this->previewUrl;
        $expectedPreviewUrl = $this->previewUrl;

        $stackOverflowJob->setPreviewUrl( $givenPreviewUrl );
        $this->assertNotNull( $stackOverflowJob->getPreviewUrl() );
        $this->assertEquals( $expectedPreviewUrl, $stackOverflowJob->getPreviewUrl() );

        $stackOverflowJob->setPreviewUrl( null );
        $this->assertNull( $stackOverflowJob->getPreviewUrl() );
    }

    public function testSetGetLogo () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenLogo = $this->logo;
        $expectedLogo = $this->logo;

        $stackOverflowJob->setLogo( $givenLogo );
        $this->assertNotNull( $stackOverflowJob->getLogo() );
        $this->assertEquals( $expectedLogo, $stackOverflowJob->getLogo() );

        $stackOverflowJob->setLogo( null );
        $this->assertNull( $stackOverflowJob->getLogo() );
    }

    public function testSetGetTitle () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenTitle = $this->title;
        $expectedTitle = $this->title;

        $stackOverflowJob->setTitle( $givenTitle );
        $this->assertNotNull( $stackOverflowJob->getTitle() );
        $this->assertEquals( $expectedTitle, $stackOverflowJob->getTitle() );

        $stackOverflowJob->setTitle( null );
        $this->assertNull( $stackOverflowJob->getTitle() );
    }

    public function testSetGetCompany () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenCompany = $this->company;
        $expectedCompany = $this->company;

        $stackOverflowJob->setCompany( $givenCompany );
        $this->assertNotNull( $stackOverflowJob->getCompany() );
        $this->assertEquals( $expectedCompany, $stackOverflowJob->getCompany() );

        $stackOverflowJob->setCompany( null );
        $this->assertNull( $stackOverflowJob->getCompany() );
    }

    public function testSetGetLocation () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenLocation = $this->location;
        $expectedLocation = $this->location;

        $stackOverflowJob->setLocation( $givenLocation );
        $this->assertNotNull( $stackOverflowJob->getLocation() );
        $this->assertEquals( $expectedLocation, $stackOverflowJob->getLocation() );

        $stackOverflowJob->setLocation( null );
        $this->assertNull( $stackOverflowJob->getLocation() );
    }

    public function testGetRefinedUrl () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        // test full URL with query parameters

        $givenPreviewUrl = "https://stackoverflow.com/jobs/123456/some-developer-role?param1=a&param2=b&param3=c";
        $expectedPreviewUrl = $this->previewUrl;

        $stackOverflowJob->setPreviewUrl( $givenPreviewUrl );

        $this->assertNotNull( $stackOverflowJob->getRefinedUrl() );
        $this->assertEquals( $expectedPreviewUrl, $stackOverflowJob->getRefinedUrl() );

        // test full URL

        $givenPreviewUrl = $this->previewUrl;
        $expectedPreviewUrl = $this->previewUrl;

        $stackOverflowJob->setPreviewUrl( $givenPreviewUrl );

        $this->assertNotNull( $stackOverflowJob->getRefinedUrl() );
        $this->assertEquals( $expectedPreviewUrl, $stackOverflowJob->getRefinedUrl() );

        // test partial URL with query parameters

        $givenPreviewUrl = "/jobs/123456/some-developer-role?param1=a&param2=b&param3=c";
        $expectedPreviewUrl = $this->previewUrl;

        $stackOverflowJob->setPreviewUrl( $givenPreviewUrl );

        $this->assertNotNull( $stackOverflowJob->getRefinedUrl() );
        $this->assertEquals( $expectedPreviewUrl, $stackOverflowJob->getRefinedUrl() );

        // test partial URL

        $givenPreviewUrl = "/jobs/123456/some-developer-role";
        $expectedPreviewUrl = $this->previewUrl;

        $stackOverflowJob->setPreviewUrl( $givenPreviewUrl );

        $this->assertNotNull( $stackOverflowJob->getRefinedUrl() );
        $this->assertEquals( $expectedPreviewUrl, $stackOverflowJob->getRefinedUrl() );
    }

    public function testAllAttributesDefinedNoAttributes () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $this->assertFalse( $stackOverflowJob->allAttributesDefined() );
    }

    public function testAllAttributesDefinedSomeAttributes () : void
    {
        $stackOverflowJob = new StackOverflowJob();
        $stackOverflowJob->setJobId( $this->jobId );

        $this->assertFalse( $stackOverflowJob->allAttributesDefined() );
    }

    public function testAllAttributesDefinedAllAttributes () : void
    {
        $stackOverflowJob = new StackOverflowJob();
        $stackOverflowJob->setJobId( $this->jobId );
        $stackOverflowJob->setResultId( $this->resultId );
        $stackOverflowJob->setPreviewUrl( $this->previewUrl );
        $stackOverflowJob->setLogo( $this->logo );
        $stackOverflowJob->setTitle( $this->title );
        $stackOverflowJob->setCompany( $this->company );
        $stackOverflowJob->setLocation( $this->location );

        $this->assertTrue( $stackOverflowJob->allAttributesDefined() );
    }
}
