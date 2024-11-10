<?php

namespace Elegantly\Referrer\Sources;

use Illuminate\Http\Request;

/**
 * @extends ReferrerSource<string|null>
 */
class RequestHeaderSource extends ReferrerSource
{
    final public function __construct(
        public ?string $referer = null,
        public ?float $timestamp = null,
    ) {
        //
    }

    public function isEmpty(): bool
    {
        return blank($this->referer);
    }

    public static function fromRequest(Request $request): static
    {
        $referer = $request->header('referer'); // spelling is a known mistake

        if (blank($referer)) {
            return new static;
        }

        if (! is_string($referer)) {
            return new static;
        }

        if (parse_url($referer, PHP_URL_HOST) === parse_url(url('/'), PHP_URL_HOST)) {
            return new static;
        }

        return new static(
            referer: $referer,
            timestamp: (float) $request->server('REQUEST_TIME')
        );
    }

    /**
     * @param array{
     *      referer?: string|null,
     *      timestamp?: float|null,
     *  } $values
     */
    public static function fromArray(array $values): static
    {
        return new static(
            referer: $values['referer'] ?? null,
            timestamp: $values['timestamp'] ?? null,
        );
    }

    /**
     * @return array{
     *      referer?: string|null,
     *      timestamp?: float|null,
     *  }
     */
    public function toArray(): array
    {
        return [
            'referer' => $this->referer,
            'timestamp' => $this->timestamp,
        ];
    }

    public function __toString(): string
    {
        return $this->referer ?? '';
    }
}
