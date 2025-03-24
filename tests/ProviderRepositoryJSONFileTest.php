<?php declare(strict_types=1);

namespace Jarek\tests;

use InvalidArgumentException;
use Jarek\DatabaseAbstractionLayer\Entity\ProviderCollection;
use Jarek\DatabaseAbstractionLayer\ProviderRepositoryJSONFile;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;

class ProviderRepositoryJSONFileTest extends TestCase
{
    private $ProviderRepositoryJSONFile;
    private $mockFilesystem;

    protected function setUp(): void
    {
        $this->mockFilesystem = $this->createMock(Filesystem::class);
        $this->ProviderRepositoryJSONFile = new ProviderRepositoryJSONFile($this->mockFilesystem); // Initialize with the mocked filesystem
    }

    public function testGetProviderDataReturnsProviderCollection()
    {
        // Define the correct JSON data
        $jsonData = json_encode([
            'provider_topics' => [
                'provider_a' => 'math+science+singing',
                'provider_b' => 'reading+science',
                'provider_c' => 'history+math',
                'provider_d' => 'art+singing+history',
                'provider_e' => 'reading+art',
                'provider_f' => 'singing+math'
            ]
        ]);

        $this->mockFilesystem
            ->method('read')
            ->willReturn($jsonData);

        $providerCollection = $this->ProviderRepositoryJSONFile->getProviderData();

        // Assert that the returned object is of type ProviderCollection
        $this->assertInstanceOf(ProviderCollection::class, $providerCollection);

        // Assert that the provider collection contains the correct number of providers
        $this->assertCount(6, $providerCollection);

        // Check the first provider (provider_a)
        $providerA = $providerCollection->first();
        $this->assertEquals('provider_a', $providerA->getName());
        $this->assertCount(3, $providerA->getTopicCollection());
        $this->assertEquals('math', $providerA->getTopicCollection()->first()->getName());
    }

    public function testGetProviderDataThrowsExceptionOnInvalidJson()
    {
        // Invalid JSON data (malformed JSON)
        $invalidJson = "{ provider_topics: { provider_a: 'math+science' }";

        // Mock the filesystem read method to return invalid JSON
        $this->mockFilesystem
            ->method('read')
            ->willReturn($invalidJson);

        // Expecting an InvalidArgumentException for invalid JSON structure
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid providers JSON data.');

        $this->ProviderRepositoryJSONFile->getProviderData();
    }

    public function testGetProviderDataThrowsExceptionOnEmptyProviderName()
    {
        // Invalid JSON with empty provider name
        $invalidJson = json_encode([
            'provider_topics' => [
                '' => 'math+science' // Empty provider name
            ]
        ]);

        // Mock the filesystem read method to return the invalid JSON
        $this->mockFilesystem
            ->method('read')
            ->willReturn($invalidJson);

        // Expecting an InvalidArgumentException due to empty provider name
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid JSON data structure - incorrect provider name.');

        $this->ProviderRepositoryJSONFile->getProviderData();
    }

    public function testGetProviderDataThrowsExceptionOnEmptyTopicName()
    {
        // Invalid JSON with empty topic name
        $invalidJson = json_encode([
            'provider_topics' => [
                'provider_a' => 'math+science+'
            ]
        ]);

        // Mock the filesystem read method to return the invalid JSON
        $this->mockFilesystem
            ->method('read')
            ->willReturn($invalidJson);

        // Expecting an InvalidArgumentException due to empty topic name
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid JSON data structure - incorrect topic names.');

        $this->ProviderRepositoryJSONFile->getProviderData();
    }

    public function testGetProviderDataThrowsExceptionOnEmptyTopicCollection()
    {
        // Invalid JSON with no topics for the provider
        $invalidJson = json_encode([
            'provider_topics' => [
                'provider_a' => '' // No topics for the provider
            ]
        ]);

        // Mock the filesystem read method to return the invalid JSON
        $this->mockFilesystem
            ->method('read')
            ->willReturn($invalidJson);

        // Expecting a RuntimeException due to no topics for the provider
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid JSON data structure - incorrect topic names.');

        $this->ProviderRepositoryJSONFile->getProviderData();
    }

    public function testGetProviderDataThrowsExceptionOnJsonDecodeFailure()
    {
        // Simulate a JSON decoding error by returning invalid JSON
        $invalidJson = "{ provider_topics: { provider_a: 'math+science' }";

        // Mock the filesystem read method to return the invalid JSON
        $this->mockFilesystem
            ->method('read')
            ->willReturn($invalidJson);

        // Expecting an InvalidArgumentException due to invalid JSON structure
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid providers JSON data.');

        $this->ProviderRepositoryJSONFile->getProviderData();
    }
}
