<?php declare( strict_types = 1 );

namespace Coco\SourceWatcher\Tests\Vendors\StackOverflow;

use Coco\SourceWatcher\Vendors\StackOverflow\StackOverflowJob;
use PHPUnit\Framework\TestCase;

/**
 * Class StackOverflowJobTest
 * @package Coco\SourceWatcher\Tests\Vendors\StackOverflow
 */
class StackOverflowJobTest extends TestCase
{
    /**
     *
     */
    public function testSetGetJobId () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenJobId = "123456";
        $expectedJobId = "123456";

        $stackOverflowJob->setJobId( $givenJobId );
        $this->assertNotNull( $stackOverflowJob->getJobId() );
        $this->assertEquals( $expectedJobId, $stackOverflowJob->getJobId() );

        $stackOverflowJob->setJobId( null );
        $this->assertNull( $stackOverflowJob->getJobId() );
    }

    /**
     *
     */
    public function testSetGetResultId () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenResultId = "123456";
        $expectedResultId = "123456";

        $stackOverflowJob->setResultId( $givenResultId );
        $this->assertNotNull( $stackOverflowJob->getResultId() );
        $this->assertEquals( $expectedResultId, $stackOverflowJob->getResultId() );

        $stackOverflowJob->setResultId( null );
        $this->assertNull( $stackOverflowJob->getResultId() );
    }

    /**
     *
     */
    public function testSetGetPreviewURL () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenPreviewUrl = "https://stackoverflow.com/jobs/123456/some-developer-role";
        $expectedPreviewUrl = "https://stackoverflow.com/jobs/123456/some-developer-role";

        $stackOverflowJob->setPreviewUrl( $givenPreviewUrl );
        $this->assertNotNull( $stackOverflowJob->getPreviewUrl() );
        $this->assertEquals( $expectedPreviewUrl, $stackOverflowJob->getPreviewUrl() );

        $stackOverflowJob->setPreviewUrl( null );
        $this->assertNull( $stackOverflowJob->getPreviewUrl() );
    }

    /**
     *
     */
    public function testSetGetLogo () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenLogo = "https://some-website.com/assets/some-logo.svg";
        $expectedLogo = "https://some-website.com/assets/some-logo.svg";

        $stackOverflowJob->setLogo( $givenLogo );
        $this->assertNotNull( $stackOverflowJob->getLogo() );
        $this->assertEquals( $expectedLogo, $stackOverflowJob->getLogo() );

        $stackOverflowJob->setLogo( null );
        $this->assertNull( $stackOverflowJob->getLogo() );
    }

    /**
     *
     */
    public function testSetGetTitle () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenTitle = "Some Developer Role";
        $expectedTitle = "Some Developer Role";

        $stackOverflowJob->setTitle( $givenTitle );
        $this->assertNotNull( $stackOverflowJob->getTitle() );
        $this->assertEquals( $expectedTitle, $stackOverflowJob->getTitle() );

        $stackOverflowJob->setTitle( null );
        $this->assertNull( $stackOverflowJob->getTitle() );
    }

    /**
     *
     */
    public function testSetGetCompany () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenCompany = "Acme Corporation";
        $expectedCompany = "Acme Corporation";

        $stackOverflowJob->setCompany( $givenCompany );
        $this->assertNotNull( $stackOverflowJob->getCompany() );
        $this->assertEquals( $expectedCompany, $stackOverflowJob->getCompany() );

        $stackOverflowJob->setCompany( null );
        $this->assertNull( $stackOverflowJob->getCompany() );
    }

    /**
     *
     */
    public function testSetGetLocation () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $givenLocation = "Saint Denis";
        $expectedLocation = "Saint Denis";

        $stackOverflowJob->setLocation( $givenLocation );
        $this->assertNotNull( $stackOverflowJob->getLocation() );
        $this->assertEquals( $expectedLocation, $stackOverflowJob->getLocation() );

        $stackOverflowJob->setLocation( null );
        $this->assertNull( $stackOverflowJob->getLocation() );
    }

    /**
     *
     */
    public function testGetRefinedUrl () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        // test full URL with query parameters

        $givenPreviewUrl = "https://stackoverflow.com/jobs/123456/some-developer-role?param1=a&param2=b&param3=c";
        $expectedPreviewUrl = "https://stackoverflow.com/jobs/123456/some-developer-role";

        $stackOverflowJob->setPreviewUrl( $givenPreviewUrl );

        $this->assertNotNull( $stackOverflowJob->getRefinedUrl() );
        $this->assertEquals( $expectedPreviewUrl, $stackOverflowJob->getRefinedUrl() );

        // test full URL

        $givenPreviewUrl = "https://stackoverflow.com/jobs/123456/some-developer-role";
        $expectedPreviewUrl = "https://stackoverflow.com/jobs/123456/some-developer-role";

        $stackOverflowJob->setPreviewUrl( $givenPreviewUrl );

        $this->assertNotNull( $stackOverflowJob->getRefinedUrl() );
        $this->assertEquals( $expectedPreviewUrl, $stackOverflowJob->getRefinedUrl() );

        // test partial URL with query parameters

        $givenPreviewUrl = "/jobs/123456/some-developer-role?param1=a&param2=b&param3=c";
        $expectedPreviewUrl = "https://stackoverflow.com/jobs/123456/some-developer-role";

        $stackOverflowJob->setPreviewUrl( $givenPreviewUrl );

        $this->assertNotNull( $stackOverflowJob->getRefinedUrl() );
        $this->assertEquals( $expectedPreviewUrl, $stackOverflowJob->getRefinedUrl() );

        // test partial URL

        $givenPreviewUrl = "/jobs/123456/some-developer-role";
        $expectedPreviewUrl = "https://stackoverflow.com/jobs/123456/some-developer-role";

        $stackOverflowJob->setPreviewUrl( $givenPreviewUrl );

        $this->assertNotNull( $stackOverflowJob->getRefinedUrl() );
        $this->assertEquals( $expectedPreviewUrl, $stackOverflowJob->getRefinedUrl() );
    }

    /**
     *
     */
    public function testAllAttributesDefinedNoAttributes () : void
    {
        $stackOverflowJob = new StackOverflowJob();

        $this->assertFalse( $stackOverflowJob->allAttributesDefined() );
    }

    /**
     *
     */
    public function testAllAttributesDefinedSomeAttributes () : void
    {
        $stackOverflowJob = new StackOverflowJob();
        $stackOverflowJob->setJobId( "123456" );

        $this->assertFalse( $stackOverflowJob->allAttributesDefined() );
    }

    /**
     *
     */
    public function testAllAttributesDefinedAllAttributes () : void
    {
        $stackOverflowJob = new StackOverflowJob();
        $stackOverflowJob->setJobId( "123456" );
        $stackOverflowJob->setResultId( "123456" );
        $stackOverflowJob->setPreviewUrl( "https://stackoverflow.com/jobs/123456/some-developer-role" );
        $stackOverflowJob->setLogo( "https://some-website.com/assets/some-logo.svg" );
        $stackOverflowJob->setTitle( "Some Developer Role" );
        $stackOverflowJob->setCompany( "Acme Corporation" );
        $stackOverflowJob->setLocation( "Saint Denis" );
        $stackOverflowJob->setPreviewUrl( "https://stackoverflow.com/jobs/123456/some-developer-role" );

        $this->assertTrue( $stackOverflowJob->allAttributesDefined() );
    }
}
