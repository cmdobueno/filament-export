<?php

namespace Cmdobueno\FilamentExport\Actions\Concerns;

trait HasCsvDelimiter
{
    protected string $csvDelimiter = ',';

    public function csvDelimiter(string $csvDelimiter): static
    {
        $this->csvDelimiter = $csvDelimiter;

        return $this;
    }

    public function getCsvDelimiter(): string
    {
        return $this->csvDelimiter;
    }
}
