<?php

use Omatech\Imaginator\Repositories\Imaginator;

function imaginatorGenUrls($id, $alt = '', $formats = [], $options = [], $sets = [])
{
    $imaginator = new Imaginator();

    $html = '';
    $urls = '';

    foreach ($formats as $format) {
        foreach ($sets as $index => $set) {
            if (!empty($set['srcset'])) {
                $srcset = array_map('trim', explode(',', $set['srcset']));
            } else {
                $srcset = [];
            }

            $html .= "<source";

            $urls = $imaginator->generateUrls([
                'hash'    => $id,
                'srcset'  => $srcset ?? [],
                'media'   => $set['media'] ?? '',
                'sizes'   => $set['sizes'] ?? '',
                'options' => $options ?? [],
                'format'  => $format
            ]);

            if (!empty($set['media'])) {
                $html .= " media='{$set['media']}'";
            }

            if (!empty($set['sizes'])) {
                $html .= " size='{$set['sizes']}'";
            }

            $html .= " srcset='{$urls['srcset']}' type='image/{$urls['format']}'>\n";
        }
    }

    $html .= "<img src='{$imaginator->generateUrls(['hash' => $id])['base']}' alt='{$alt}'>";
    
    return $html;
}
