<?php

namespace App\Filesystem;

use League\Flysystem\Config;
use League\Flysystem\FileAttributes;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\StorageAttributes;
use Illuminate\Support\Facades\Http;
use League\Flysystem\UnableToCheckExistence;
use League\Flysystem\UnableToReadFile;
use League\Flysystem\UnableToWriteFile;
use League\Flysystem\UnableToDeleteFile;

class SupabaseStorageAdapter implements FilesystemAdapter
{
    protected string $endpoint;
    protected string $key;
    protected string $bucket;

    public function __construct(string $endpoint, string $key, string $bucket)
    {
        $this->endpoint = rtrim($endpoint, '/');
        // Clean up the endpoint to target the storage API endpoint: https://{ref}.supabase.co/storage/v1
        if (str_contains($this->endpoint, '.supabase.co')) {
            $parts = parse_url($this->endpoint);
            $this->endpoint = ($parts['scheme'] ?? 'https') . '://' . ($parts['host'] ?? '') . '/storage/v1';
        }
        $this->key = $key;
        $this->bucket = $bucket;
    }

    public function getUrl(string $path): string
    {
        return "{$this->endpoint}/object/public/{$this->bucket}/" . ltrim($path, '/');
    }

    protected function getApiUrl(string $path): string
    {
        return "{$this->endpoint}/object/{$this->bucket}/" . ltrim($path, '/');
    }

    protected function getHeaders(): array
    {
        return [
            'Authorization' => "Bearer {$this->key}",
            'apikey' => $this->key,
        ];
    }

    public function fileExists(string $path): bool
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->head($this->getApiUrl($path));
            return $response->status() === 200;
        } catch (\Exception $e) {
            throw UnableToCheckExistence::forLocation($path, $e);
        }
    }

    public function directoryExists(string $path): bool
    {
        return false;
    }

    public function write(string $path, string $contents, Config $config): void
    {
        try {
            // Determine MIME type
            $mimeType = $config->get('mime_type') ?? $config->get('mimetype') ?? 'application/octet-stream';
            if ($mimeType === 'application/octet-stream') {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($contents) ?: 'application/octet-stream';
            }

            $response = Http::withHeaders(array_merge($this->getHeaders(), [
                'x-upsert' => 'true',
                'Content-Type' => $mimeType,
            ]))->withBody($contents, $mimeType)->post($this->getApiUrl($path));

            if (!$response->successful()) {
                throw new \Exception("Upload failed with status: " . $response->status() . " - " . $response->body());
            }
        } catch (\Exception $e) {
            throw UnableToWriteFile::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function writeStream(string $path, $contents, Config $config): void
    {
        $raw = stream_get_contents($contents);
        if ($raw === false) {
            throw UnableToWriteFile::atLocation($path, 'Could not read from stream');
        }
        $this->write($path, $raw, $config);
    }

    public function read(string $path): string
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get($this->getApiUrl($path));
            if (!$response->successful()) {
                throw new \Exception("Read failed with status: " . $response->status());
            }
            return $response->body();
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage(), $e);
        }
    }

    public function readStream(string $path)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->get($this->getApiUrl($path));
            if (!$response->successful()) {
                throw new \Exception("Read stream failed with status: " . $response->status());
            }
            $stream = fopen('php://temp', 'r+');
            fwrite($stream, $response->body());
            rewind($stream);
            return $stream;
        } catch (\Exception $e) {
            throw UnableToReadFile::fromLocation($path, $e->getMessage(), $e);
        }
    }

    public function delete(string $path): void
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->delete($this->getApiUrl($path));
            if ($response->status() !== 200 && $response->status() !== 404) {
                throw new \Exception("Delete failed with status: " . $response->status());
            }
        } catch (\Exception $e) {
            throw UnableToDeleteFile::atLocation($path, $e->getMessage(), $e);
        }
    }

    public function deleteDirectory(string $path): void
    {
        // Noop
    }

    public function createDirectory(string $path, Config $config): void
    {
        // Noop
    }

    public function setVisibility(string $path, string $visibility): void
    {
        // Noop
    }

    public function visibility(string $path): FileAttributes
    {
        return new FileAttributes($path, null, 'public');
    }

    public function mimeType(string $path): FileAttributes
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->head($this->getApiUrl($path));
            $headers = $response->headers();
            $mimeType = $headers['Content-Type'][0] ?? $headers['content-type'][0] ?? 'application/octet-stream';
            return new FileAttributes($path, null, null, null, $mimeType);
        } catch (\Exception $e) {
            return new FileAttributes($path);
        }
    }

    public function lastModified(string $path): FileAttributes
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->head($this->getApiUrl($path));
            $headers = $response->headers();
            $lastModified = isset($headers['Last-Modified'][0]) ? strtotime($headers['Last-Modified'][0]) : time();
            return new FileAttributes($path, null, null, $lastModified);
        } catch (\Exception $e) {
            return new FileAttributes($path, null, null, time());
        }
    }

    public function fileSize(string $path): FileAttributes
    {
        try {
            $response = Http::withHeaders($this->getHeaders())->head($this->getApiUrl($path));
            $headers = $response->headers();
            $size = isset($headers['Content-Length'][0]) ? (int)$headers['Content-Length'][0] : 0;
            return new FileAttributes($path, $size);
        } catch (\Exception $e) {
            return new FileAttributes($path, 0);
        }
    }

    public function listContents(string $path, bool $deep): iterable
    {
        return [];
    }

    public function move(string $source, string $destination, Config $config): void
    {
        // Noop
    }

    public function copy(string $source, string $destination, Config $config): void
    {
        // Noop
    }
}
