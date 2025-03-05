<?php

declare(strict_types=1);

namespace Infrastructure\Http\Interceptor;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

use function strlen;

/**
 *
 */
class AddHttp2ServerPush
{
    /**
     * The DomCrawler instance.
     *
     * @var Crawler|null
     */
    protected ?Crawler $crawler = null;

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $limit
     * @param null $sizeLimit
     * @param null $excludeKeywords
     * @return mixed
     */
    public function handle(
        Request $request,
        Closure $next,
        $limit = null,
        $sizeLimit = null,
        $excludeKeywords = null
    ): mixed
    {
        $response = $next($request);

        if (!$response instanceof Response || $response->isRedirection() || $request->isJson()) {
            return $response;
        }

        $this->generateAndAttachLinkHeaders($response, $limit, $sizeLimit, $excludeKeywords);

        return $response;
    }

    /**
     * @param Response $response
     * @param null $limit
     * @param null $sizeLimit
     * @param null $excludeKeywords
     * @return $this
     */
    protected function generateAndAttachLinkHeaders(
        Response $response,
        $limit = null,
        $sizeLimit = null,
        $excludeKeywords = null
    ): static
    {
        $excludeKeywords = $excludeKeywords ?? $this->getConfig('exclude_keywords', []);
        $headers = $this->fetchLinkableNodes($response)->flatten(1)->map(function ($url) {
                return $this->buildLinkHeaderString($url);
        })->unique()->filter(function ($value) use ($excludeKeywords) {
            if (!$value) {
                return false;
            }
            $exclude_keywords = collect($excludeKeywords)->map(function ($keyword) {
                /**
                 * @noinspection PregQuoteUsageInspection
                 */
                return preg_quote($keyword);
            });
            if ($exclude_keywords->count() <= 0) {
                return true;
            }

            return !preg_match('%(' . $exclude_keywords->implode('|') . ')%i', $value);
        })->take($limit);

        $sizeLimit = $sizeLimit ?? max(1, (int) $this->getConfig('nyt.push.size_limit', 32 * 1024));
        $headersText = trim($headers->implode(','));

        while (strlen($headersText) > $sizeLimit) {
            $headers->pop();
            $headersText = trim($headers->implode(','));
        }

        if (!empty($headersText)) {
            $this->addLinkHeader($response, $headersText);
        }

        return $this;
    }

    /**
     * @param $key
     * @param false $default
     * @return mixed
     */
    public function getConfig($key, $default = false): mixed
    {
        return config('nyt.push.' . $key, $default);
    }

    /**
     * Get all nodes we are interested in pushing.
     *
     * @param Response $response
     *
     * @return Collection
     */
    protected function fetchLinkableNodes($response): Collection
    {
        $craw = $this->getCrawler($response);

        return collect(
            $craw->filter('link:not([rel*="icon"]), script[src], img[src], object[data]')->extract(
                ['src', 'href', 'data']
            )
        );
    }

    /**
     * Get the DomCrawler instance.
     *
     * @param Response $response
     *
     * @return Crawler
     */
    protected function getCrawler(Response $response): Crawler
    {
        if ($this->crawler) {
            return $this->crawler;
        }

        return $this->crawler = new Crawler($response->getContent());
    }

    /**
     * Build out header string based on asset extension.
     *
     * @param string $url
     *
     * @return string|null
     */
    private function buildLinkHeaderString($url): ?string
    {
        $linkTypeMap = [
            '.CSS' => 'style',
            '.JS' => 'script',
            '.BMP' => 'image',
            '.GIF' => 'image',
            '.JPG' => 'image',
            '.JPEG' => 'image',
            '.PNG' => 'image',
            '.SVG' => 'image',
            '.TIFF' => 'image',
            '.WEBP' => 'image',
            '.jp2' => 'image',
        ];

        $type = collect($linkTypeMap)->first(fn($type, $extension) => Str::contains(strtoupper($url), $extension));

        if ($url && !$type) {
            $type = 'fetch';
        }

        if (!preg_match('%^(https?:)?//%i', $url)) {
            $basePath = $this->getConfig('base_path', '/');
            $url = $basePath . ltrim($url, $basePath);
        }

        return null === $type ? null : "<{$url}>; rel=preload; as={$type}";
    }

    /**
     * Add Link Header
     *
     * @param Response $response
     *
     * @param $link
     */
    private function addLinkHeader(Response $response, $link): void
    {
        if ($response->headers->get('Link')) {
            $link = $response->headers->get('Link') . ',' . $link;
        }

        $response->header('Link', $link);
    }
}
