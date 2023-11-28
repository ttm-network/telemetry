<?php

declare(strict_types=1);

namespace TTM\Telemetry;

use function basename;
use function count;
use function get_class;
use function sprintf;
use function str_repeat;

use Throwable;

/**
 * @psalm-type Frame = array{
 *     function: string,
 *     class: ?class-string,
 *     file: ?string,
 *     line: ?int,
 * }
 * @psalm-type Frames = non-empty-list<Frame>
 */
final class StackTraceFormatter implements StackTraceFormatterInterface
{
    /**
     * @inheritDoc
     */
    public function format(Throwable $e): string
    {
        $s = '';
        $seen = [];

        /** @var Frames|null $enclosing */
        $enclosing = null;
        do {
            if ($enclosing) {
                $this->writeNewline($s);
                $s .= 'Caused by: ';
            }
            if (isset($seen[spl_object_id($e)])) {
                $s .= '[CIRCULAR REFERENCE: ';
                $this->writeInlineHeader($s, $e);
                $s .= ']';

                break;
            }
            $seen[spl_object_id($e)] = $e;

            $frames = $this->frames($e);
            $this->writeInlineHeader($s, $e);
            $this->writeFrames($s, $frames, $enclosing);

            $enclosing = $frames;
        } while ($e = $e->getPrevious());

        return $s;
    }

    /**
     * @phan-suppress-next-line PhanTypeMismatchDeclaredParam
     * @param Frames $frames
     * @phan-suppress-next-line PhanTypeMismatchDeclaredParam
     * @param Frames|null $enclosing
     */
    private function writeFrames(string &$s, array $frames, ?array $enclosing): void
    {
        $n = count($frames);
        if ($enclosing) {
            for (
                $m = count($enclosing);
                $n && $m && $frames[$n - 1] === $enclosing[$m - 1];
                $n--, $m--
            ) {
            }
        }
        for ($i = 0; $i < $n; $i++) {
            $frame = $frames[$i];
            $this->writeNewline($s, 1);
            $s .= 'at ';
            if ($frame['class'] !== null) {
                $s .= $this->formatName($frame['class']);
                $s .= '.';
            }
            $s .= $this->formatName($frame['function']);
            $s .= '(';
            if ($frame['file'] !== null) {
                $s .= basename($frame['file']);
                if ($frame['line']) {
                    $s .= ':';
                    $s .= $frame['line'];
                }
            } else {
                $s .= 'Unknown Source';
            }
            $s .= ')';
        }
        if ($n !== count($frames)) {
            $this->writeNewline($s, 1);
            $s .= sprintf('... %d more', count($frames) - $n);
        }
    }

    private function writeInlineHeader(string &$s, Throwable $e): void
    {
        $s .= $this->formatName(get_class($e));
        if ($e->getMessage() !== '') {
            $s .= ': ';
            $s .= $e->getMessage();
        }
    }

    private function writeNewline(string &$s, int $indent = 0): void
    {
        $s .= "\n";
        $s .= str_repeat("\t", $indent);
    }

    /**
     * @return Frames
     *
     * @psalm-suppress PossiblyUndefinedArrayOffset
     */
    private function frames(Throwable $e): array
    {
        $frames = [];
        $trace = $e->getTrace();
        $traceCount = count($trace);
        for ($i = 0; $i < $traceCount + 1; $i++) {
            $frames[] = [
                'function' => $trace[$i]['function'] ?? '{main}',
                'class' => $trace[$i]['class'] ?? null,
                'file' => $trace[$i - 1]['file'] ?? null,
                'line' => $trace[$i - 1]['line'] ?? null,
            ];
        }
        $frames[0]['file'] = $e->getFile();
        $frames[0]['line'] = $e->getLine();

        /** @var Frames $frames */
        return $frames;
    }

    private function formatName(string $name): string
    {
        return strtr($name, ['\\' => '.']);
    }
}
