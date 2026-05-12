<?php

declare(strict_types=1);

/*
 * This file is part of the package jweiland/service_bw2.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace JWeiland\ServiceBw2\Tests\Functional\Client;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JWeiland\ServiceBw2\Client\Request\Portal\Lebenslagen;
use JWeiland\ServiceBw2\Client\ServiceBwClient;
use JWeiland\ServiceBw2\Configuration\ExtConf;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Log\Logger;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ServiceBwClientTest extends FunctionalTestCase
{
    protected ServiceBwClient $subject;

    protected RequestFactory|MockObject $requestFactoryMock;

    protected Logger|MockObject $loggerMock;

    protected array $testExtensionsToLoad = [
        'jweiland/service-bw2',
        'jweiland/maps2',
        'typo3/cms-scheduler',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->requestFactoryMock = $this->createMock(RequestFactory::class);
        $this->loggerMock = $this->createMock(Logger::class);
        $extConf = new ExtConf(
            '123',
            'abc123',
            'https://sgw.service-bw.de:443/rest-v2/api',
            'de=de;en=en;fr=en',
            12,
            '',
        );

        $this->subject = new ServiceBwClient(
            $this->requestFactoryMock,
            $extConf,
            $this->loggerMock,
        );
    }

    #[Test]
    public function requestAllWithStatusCode404WillAddErrorLog(): void
    {
        $response = $this->createMock(Response::class);
        $response
            ->expects($this->atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(404);

        $this->requestFactoryMock
            ->expects($this->atLeastOnce())
            ->method('request')
            ->willReturn($response);

        $this->loggerMock
            ->expects($this->atLeastOnce())
            ->method('error')
            ->with(self::stringStartsWith('Service BW API record was not found'));

        iterator_to_array($this->subject->requestAll(new Lebenslagen(), 'de'));
    }

    #[Test]
    public function requestAllWithStatusCode503WillAddErrorLog(): void
    {
        $response = $this->createMock(Response::class);
        $response
            ->expects($this->atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(503);

        $this->requestFactoryMock
            ->expects($this->atLeastOnce())
            ->method('request')
            ->willReturn($response);

        $this->loggerMock
            ->expects($this->atLeastOnce())
            ->method('error')
            ->with(self::stringStartsWith('Service BW API responded with an unexpected status code.'));

        iterator_to_array($this->subject->requestAll(new Lebenslagen(), 'de'));
    }

    #[Test]
    public function requestAllWillReturnResponseData(): void
    {
        $data = [
            'currentPage' => 0,
            'totalPages' => 1,
            'items' => [
                0 => [
                    'id' => 123,
                ],
            ],
        ];

        $this->requestFactoryMock
            ->expects($this->atLeastOnce())
            ->method('request')
            ->willReturn(new Response(200, [], json_encode($data)));

        $items = iterator_to_array($this->subject->requestAll(new Lebenslagen(), 'de'));

        self::assertSame(
            [
                123 => ['id' => 123],
            ],
            $items,
        );
    }

    #[Test]
    public function requestRecordWithStatusCode503WillRetryThreeTimes(): void
    {
        $this->expectExceptionMessage('An error was encountered while creating the response');
        $this->expectException(RequestException::class);

        $response = $this->createMock(Response::class);
        $response
            ->expects($this->atLeastOnce())
            ->method('getStatusCode')
            ->willReturn(503);

        $guzzleRequestException = new RequestException(
            'An error was encountered while creating the response',
            new Request('GET', 'https://example.com'),
            $response,
        );

        $this->requestFactoryMock
            ->expects($this->atLeastOnce())
            ->method('request')
            ->willThrowException($guzzleRequestException);

        $this->subject->requestRecord(new Lebenslagen(), 'de');
    }

    #[Test]
    public function requestRecordWithConnectionExceptionWillRetryThreeTimes(): void
    {
        $this->expectExceptionMessage('cURL error 28: Connection timed out after 10007');
        $this->expectException(ConnectException::class);

        $guzzleConnectException = new ConnectException(
            'cURL error 28: Connection timed out after 10007',
            new Request('GET', 'https://example.com'),
        );

        $this->requestFactoryMock
            ->expects($this->atLeastOnce())
            ->method('request')
            ->willThrowException($guzzleConnectException);

        $this->subject->requestRecord(new Lebenslagen(), 'de');
    }

    #[Test]
    public function requestRecordWillReturnResponseData(): void
    {
        $data = [
            'id' => 123,
        ];

        $this->requestFactoryMock
            ->expects($this->atLeastOnce())
            ->method('request')
            ->willReturn(new Response(200, [], json_encode($data)));

        $responseData = $this->subject->requestRecord(new Lebenslagen(), 'de');

        self::assertSame(
            $data,
            $responseData,
        );
    }
}
