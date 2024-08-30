<?php

namespace Elegantly\Referer\Sources;

use Illuminate\Http\Request;

/**
 * @extends RefererSource<string|null>
 */
class RequestHeaderSource extends RefererSource
{
    final public function __construct(
        public ?string $referer = null,

    ) {
        //
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            referer: $request->header('referer'),
        );
    }

    /**
     * @param array{
     *      referer?: string|null,
     *  } $values
     */
    public static function fromArray(array $values): static
    {
        return new static(
            referer: $values['referer'] ?? null,
        );
    }

    /**
     * @return array{
     *      referer?: string|null,
     *  }
     */
    public function toArray(): array
    {
        return [
            'referer' => $this->referer,
        ];
    }
}
