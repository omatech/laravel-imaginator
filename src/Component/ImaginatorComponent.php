<?php
namespace Omatech\Imaginator\Component;

use Illuminate\View\Component;
use Omatech\Imaginator\Repositories\Imaginator;

class ImaginatorComponent extends Component
{
    private $imaginator;
    public $id;
    public $alt;
    public $formats;
    public $options;
    public $sets;
    public $loading;
    public $width='';
    public $height='';

    public function __construct(
        Imaginator $imaginator,
        $id = '',
        $alt = '',
        $formats = [],
        $options = [],
        $sets = [],
        $loading = null
    ) {
        $this->imaginator = $imaginator;
        $this->id = $id;
        $this->alt = $alt;
        $this->formats = $formats;
        $this->options = $options;
        $this->sets = $sets;
        $this->loading = $loading ?? 'eager';
    }

    private function imaginatorGenUrls()
    {
        $html = '';
        $urls = '';

        foreach ($this->formats as $format) {
            foreach ($this->sets as $set) {
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

                $urls = $this->imaginator->generateUrls([
                    'hash' => $currentUrl ?? $this->id,
                    'srcset' => $srcset ?? [],
                    'media' => $set['media'] ?? '',
                    'sizes' => $set['sizes'] ?? '',
                    'options' => $this->options ?? [],
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

        $html .= "<img src='{$this->imaginator->generateUrls(['hash' => $this->id, 'options'=> $this->options])['base']}' alt='{$this->alt}' loading={$this->loading} {$this->width} {$this->height}>";
        return $html;
    }


    public function render()
    {
        return function (array $data) {
            $attributes = $data['attributes']->filter(fn ($attribute, $key) => !is_array($attribute) && $key !== 'id' && $key !== 'alt' && $key!=='width' && $key!=='height');

            if (isset($data['attributes']['width'])) {
                $this->width=' width="'.$data['attributes']['width'].'"';
            }
            if (isset($data['attributes']['height'])) {
                $this->height=' height="'.$data['attributes']['height'].'"';
            }

            return "<picture {$attributes->__toString()}>
                {$this->imaginatorGenUrls()}
            </picture>";
        };
    }
}
