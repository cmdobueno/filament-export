<?php

namespace Cmdobueno\FilamentExport\Actions\Concerns;

use Cmdobueno\FilamentExport\FilamentExport;

trait HasDefaultFormat
{
    protected string $defaultFormat;

    public function defaultFormat(string $defaultFormat): static
    {
        if (! array_key_exists($defaultFormat, FilamentExport::DEFAULT_FORMATS)) {
            return $this;
        }

        $this->defaultFormat = $defaultFormat;

        return $this;
    }

    public function getDefaultFormat(): string
    {
        return $this->defaultFormat;
    }
}
