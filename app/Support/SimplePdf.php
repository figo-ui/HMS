<?php

namespace App\Support;

class SimplePdf
{
    /**
     * @param  list<string>  $lines
     */
    public static function textDocument(string $title, array $lines): string
    {
        $safeLines = array_map(
            fn (string $line): string => static::escapeText($line),
            array_values(array_filter($lines, fn (?string $line): bool => filled($line)))
        );

        array_unshift($safeLines, static::escapeText($title));

        $content = 'BT /F1 16 Tf 50 780 Td (' . array_shift($safeLines) . ') Tj';

        if ($safeLines !== []) {
            $content .= ' /F1 11 Tf 0 -28 Td';

            foreach ($safeLines as $index => $line) {
                if ($index > 0) {
                    $content .= ' 0 -18 Td';
                }

                $content .= ' (' . $line . ') Tj';
            }
        }

        $content .= ' ET';

        $objects = [
            '1 0 obj << /Type /Catalog /Pages 2 0 R >> endobj',
            '2 0 obj << /Type /Pages /Kids [3 0 R] /Count 1 >> endobj',
            '3 0 obj << /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >> endobj',
            '4 0 obj << /Type /Font /Subtype /Type1 /BaseFont /Helvetica >> endobj',
            '5 0 obj << /Length ' . strlen($content) . " >> stream\n{$content}\nendstream endobj",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [];

        foreach ($objects as $object) {
            $offsets[] = strlen($pdf);
            $pdf .= $object . "\n";
        }

        $xrefOffset = strlen($pdf);
        $pdf .= 'xref' . "\n";
        $pdf .= '0 ' . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        foreach ($offsets as $offset) {
            $pdf .= str_pad((string) $offset, 10, '0', STR_PAD_LEFT) . " 00000 n \n";
        }

        $pdf .= 'trailer << /Size ' . (count($objects) + 1) . " /Root 1 0 R >>\n";
        $pdf .= "startxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }

    private static function escapeText(string $value): string
    {
        $ascii = preg_replace('/[^\x20-\x7E]/', '', $value) ?? '';

        return str_replace(
            ['\\', '(', ')'],
            ['\\\\', '\(', '\)'],
            $ascii
        );
    }
}
