<?php

declare(strict_types=1);

/*
 * This file is part of the Neo4j PHP Client and Driver package.
 *
 * (c) Nagels <https://nagels.tech>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Laudis\Neo4j\Http;

use function json_encode;
use Laudis\Neo4j\Common\ConnectionConfiguration;
use Laudis\Neo4j\Common\Resolvable;
use Laudis\Neo4j\Contracts\AuthenticateInterface;
use Laudis\Neo4j\Contracts\ConnectionPoolInterface;
use Laudis\Neo4j\Databags\DatabaseInfo;
use Laudis\Neo4j\Databags\DriverConfiguration;
use Laudis\Neo4j\Databags\SessionConfiguration;
use Laudis\Neo4j\Enum\ConnectionProtocol;
use Laudis\Neo4j\Formatter\BasicFormatter;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UriInterface;
use Throwable;

/**
 * @implements ConnectionPoolInterface<ClientInterface>
 */
final class HttpConnectionPool implements ConnectionPoolInterface
{
    /**
     * @var Resolvable<ClientInterface>
     * @psalm-readonly
     */
    private Resolvable $client;
    /**
     * @var Resolvable<RequestFactory>
     * @psalm-readonly
     */
    private Resolvable $requestFactory;
    /**
     * @var Resolvable<StreamFactoryInterface>
     * @psalm-readonly
     */
    private Resolvable $streamFactory;
    /** @psalm-readonly */
    private DriverConfiguration $config;

    /**
     * @param Resolvable<StreamFactoryInterface> $streamFactory
     * @param Resolvable<RequestFactory>         $requestFactory
     * @param Resolvable<ClientInterface>        $client
     * @psalm-mutation-free
     */
    public function __construct(Resolvable $client, Resolvable $requestFactory, Resolvable $streamFactory, DriverConfiguration $config)
    {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->streamFactory = $streamFactory;
        $this->config = $config;
    }

    public function acquire(
        UriInterface $uri,
        AuthenticateInterface $authenticate,
        SessionConfiguration $config
    ): HttpConnection {
        $request = $this->requestFactory->resolve()->createRequest('POST', $uri);

        $path = $request->getUri()->getPath().'/commit';
        $uri = $request->getUri()->withPath($path);
        $request = $request->withUri($uri);

        $body = json_encode([
            'statements' => [
                [
                    'statement' => <<<'CYPHER'
CALL dbms.components()
YIELD name, versions, edition
RETURN name, versions, edition
CYPHER
                ],
            ],
            'resultDataContents' => [],
            'includeStats' => false,
        ], JSON_THROW_ON_ERROR);

        $request = $request->withBody($this->streamFactory->resolve()->createStream($body));

        $response = $this->client->resolve()->sendRequest($request);
        $data = HttpHelper::interpretResponse($response);
        /** @var array{0: array{name: string, versions: list<string>, edition: string}} $results */
        $results = (new BasicFormatter())->formatHttpResult($response, $data, null)->first();

        $version = $results[0]['versions'][0] ?? '';

        $config = new ConnectionConfiguration(
            $results[0]['name'].'-'.$results[0]['edition'].'/'.$version,
            $uri,
            $version,
            ConnectionProtocol::HTTP(),
            $config->getAccessMode(),
            $this->config,
            new DatabaseInfo($config->getDatabase() ?? '')
        );

        return new HttpConnection($this->client->resolve(), $config);
    }

    public function canConnect(UriInterface $uri, AuthenticateInterface $authenticate, ?string $userAgent = null): bool
    {
        $request = $this->requestFactory->resolve()->createRequest('GET', $uri);
        $client = $this->client->resolve();

        try {
            return $client->sendRequest($request)->getStatusCode() === 200;
        } catch (Throwable $e) {
            return false;
        }
    }
}
