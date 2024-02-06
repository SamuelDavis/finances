<?php

namespace App;

use DomainException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use UnitEnum;

class Csv
{
    public readonly string $name;
    public readonly array $data;
    private array $headers;
    private array $rows;

    public function __construct(string $name, array $data)
    {
        $this->name = $name;
        $this->data = $data;
    }

    public static function fromUploadedFile(UploadedFile $file): static
    {
        if ($file->getMimeType() !== "text/csv") {
            throw new InvalidArgumentException(
                "Cannot parse {$file->getMimeType()}",
            );
        }

        $stream = fopen($file->path(), "r");
        $data = [];
        while (($row = fgetcsv($stream)) !== false) {
            $data[] = $row;
        }
        fclose($stream);

        return new static($file->getClientOriginalName(), $data);
    }

    public function getRows(): array
    {
        return $this->rows ?? array_slice($this->data ?? [], 1);
    }

    public function headersHaveBeenSet(): bool
    {
        $enumHeaders = array_map(
            fn(UnitEnum $case) => $case->name,
            Headers::cases(),
        );
        $csvHeaders = $this->getHeaders();
        $headerDiff = array_diff($enumHeaders, $csvHeaders);
        return empty($headerDiff);
    }

    public function getHeaders(): array
    {
        return $this->headers ?? ($this->data[0] ?? []);
    }

    public function setHeaders(array $headers): void
    {
        $currentHeaders = $this->data[0] ?? [];
        $correctHeaders = [];
        foreach ($headers as $case => $header) {
            $index = array_search($header, $currentHeaders);
            if ($index === false) {
                throw new DomainException(
                    sprintf(
                        "%s is not in %s",
                        $header,
                        Arr::join($currentHeaders, ","),
                    ),
                );
            }
            $correctHeaders[$case] = $index;
        }

        $this->rows = Arr::map(array_slice($this->data, 1), function (
            array $row,
        ) use ($correctHeaders) {
            $correctedRow = [];
            foreach ($correctHeaders as $header => $index) {
                $correctedRow[$header] = $row[$index];
            }
            return $correctedRow;
        });
        $this->headers = array_keys($correctHeaders);
    }
}
