<?php

namespace App\Infrastructure\Component\HttpClient;

use App\Domain\Component\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class SymfonyHttpClient implements HttpClientInterface
{
    private const DEFAULT_TTL = 3600 * 24 * 365;

    private readonly ?LoggerInterface $logger;

    public function __construct(private \Symfony\Contracts\HttpClient\HttpClientInterface $httpClient, private readonly CacheInterface $cache, LoggerInterface $httpClientLogger)
    {
        $this->logger = $httpClientLogger;
    }

    public function request(string $method, string $path, array $body): array
    {
        $cacheKey = $this->getCacheKey($method, $path, $body);
        $this->log(sprintf('[request]: "%s" "%s"', $method, $path), ['body' => json_encode($body), 'cache_key' => $cacheKey]);

        $responseContent = $this->cache->get($cacheKey, function (ItemInterface $item) use ($method, $path, $body): string {
            $item->expiresAfter(self::DEFAULT_TTL);

            $this->log('[client]: sending request');
            $response = $this->httpClient->request($method, $path, $body);

            if ($response->getStatusCode() !== Response::HTTP_OK) {
                $this->log(sprintf('[response]: "%s"', $response->getStatusCode()), ['body' => $response->getContent(),]);

                throw new RuntimeException($response->getContent());
            }

            return $response->getContent();
        });

        $this->log(sprintf('[response]: "%s"', Response::HTTP_OK), ['body' => $responseContent,]);

        return json_decode($responseContent, true);
    }

    public function withOptions(array $array): void
    {
        $this->httpClient = $this->httpClient->withOptions($array);
    }

    private function getCacheKey(string $method, string $path, array $body): string
    {
        return hash('sha256', sprintf('%s %s: %s', $method, $path, json_encode($body)));
    }

    private function log(string $message, array $context = []): void
    {
        if (!$this->logger) {
            return;
        }
        $this->logger->info($message, $context);
    }
}
