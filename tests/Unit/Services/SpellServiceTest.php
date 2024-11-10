<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\SpellService;
use App\Models\Spell;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpellServiceTest extends TestCase
{
    use RefreshDatabase;

    private SpellService $spellService;
    private MockHandler $mockHandler;
    private static bool $databaseMessageShown = false;

    protected function setUp(): void
    {
        parent::setUp();

        // Create mock for the api responses
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        // Create service with mocked client
        $this->spellService = new SpellService($client);
    }


    // deleted database message
    protected function tearDown(): void
    {
        parent::tearDown();
        
        if (!self::$databaseMessageShown) {
            echo "\nDatabase deleted successfully ✓\n";
            self::$databaseMessageShown = true;
        }
    }

    #[Test]
    public function it_can_fetch_and_import_spells()
    {
        // Given: mock api response
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                [
                    'spell' => 'Accio',
                    'use' => 'Summoning charm',
                    'index' => 0
                ],
                [
                    'spell' => 'Lumos',
                    'use' => 'Creates light',
                    'index' => 1
                ]
            ]))
        );

        // When
        $stats = $this->spellService->import();

        // Then
        $this->assertEquals(2, $stats['total']);
        $this->assertEquals(2, $stats['created']);
        $this->assertEquals(0, $stats['updated']);
        $this->assertEquals(0, $stats['failed']);

        $this->assertDatabaseHas('spells', [
            'spell' => 'Accio',
            'use' => 'Summoning charm',
            'api_index' => 0
        ]);
    }

    #[Test]
    public function it_updates_existing_spells()
    {
        // Given: existing spell in db
        Spell::create([
            'spell' => 'Accio',
            'use' => 'Old description',
            'api_index' => 0
        ]);

        // Mock api Response
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                [
                    'spell' => 'Accio',
                    'use' => 'Summoning charm',
                    'index' => 0
                ]
            ]))
        );

        // When
        $stats = $this->spellService->import();

        // Then
        $this->assertEquals(1, $stats['total']);
        $this->assertEquals(0, $stats['created']);
        $this->assertEquals(1, $stats['updated']);

        $this->assertDatabaseHas('spells', [
            'spell' => 'Accio',
            'use' => 'Summoning charm'
        ]);
    }

    #[Test]
    public function it_handles_api_errors()
    {
        // Given: mock api error
        $this->mockHandler->append(
            new Response(500, [], 'Server Error')
        );

        // Then
        $this->expectException(\Exception::class);
        
        // When
        $this->spellService->import();
    }

    #[Test]
    public function it_handles_invalid_data()
    {
        // Given: mock invalid api response
        $this->mockHandler->append(
            new Response(200, [], json_encode([
                [
                    'invalid' => 'data'  // Missing required fields
                ]
            ]))
        );

        // When
        $stats = $this->spellService->import();

        // Then
        $this->assertEquals(1, $stats['total']);
        $this->assertEquals(0, $stats['created']);
        $this->assertEquals(0, $stats['updated']);
        $this->assertEquals(1, $stats['failed']);
    }
}
