<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;

class QrCodeService
{
    public function generate(string $data, int $size = 150, int $margin = 10): string
    {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: $data,
            encoding: new Encoding('UTF-8'),
            size: $size,
            margin: $margin,
        );

        $result = $builder->build();

        return 'data:image/png;base64,' . base64_encode($result->getString());
    }
}
