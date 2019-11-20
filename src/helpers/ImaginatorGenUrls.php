<?php

use Omatech\Imaginator\Repositories\Imaginator;

if (! function_exists('imaginatorGenUrls')) {

    /**
     * @param $id
     * @param string $alt
     * @param array $formats
     * @param array $options
     * @param array $sets
     * @return string
     */
    function imaginatorGenUrls($id, $alt = '', $formats = [], $options = [], $sets = [])
    {
        $imaginator = new Imaginator();

        $html = '';
        $urls = '';

        foreach ($formats as $format) {
            foreach ($sets as $index => $set) {
                if (!empty($set['srcset'])) {
                    if (gettype($set['srcset']) == 'array') {
                        $currentUrl = $set['id'] ?? null;
                        $srcset = $set['srcset'];
                    } elseif (gettype($set['srcset']) === 'integer') {
                        $currentUrl = $set['id'] ?? null;
                        $srcset = [$set['srcset'] => $set['srcset']];
                    } else {
                        $currentUrl = null;
                        $srcset = [];
                    }
                } else {
                    $currentUrl = null;
                    $srcset = [];
                }

                $html .= "<source";

                $urls = $imaginator->generateUrls([
                    'hash' => $currentUrl ?? $id,
                    'srcset' => $srcset ?? [],
                    'media' => $set['media'] ?? '',
                    'sizes' => $set['sizes'] ?? '',
                    'options' => $options ?? [],
                    'format' => $format
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

        $html .= "<img src='{$imaginator->generateUrls(['hash' => $id, 'options'=> $options])['base']}' alt='{$alt}'>";

        return $html;
    }
}
