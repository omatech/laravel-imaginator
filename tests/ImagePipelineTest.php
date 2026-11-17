<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Glide\Signatures\SignatureFactory;
use Omatech\Imaginator\Exceptions\SecurityException;
use Omatech\Imaginator\ImaginatorServiceProvider;
use Omatech\Imaginator\Middlewares\GlideSecurityMiddleware;
use Omatech\Imaginator\Repositories\Imaginator;
use Orchestra\Testbench\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ImagePipelineTest extends TestCase
{
    protected function getPackageProviders($app): array { return [ImaginatorServiceProvider::class]; }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('imaginator.key', 'synthetic-signing-key');
        $app['config']->set('imaginator.server', 'images.fixture.test');
        $app['config']->set('imaginator.scheme', 'https');
    }

    public function test_generated_srcsets_have_signatures_bound_to_the_path_and_size(): void
    {
        $urls = (new Imaginator())->generateUrls(['hash' => 'picture.png', 'srcset' => [320, 640], 'format' => 'webp']);
        self::assertSame('webp', $urls['format']);
        foreach (explode(', ', $urls['srcset']) as $entry) {
            [$url, $width] = explode(' ', $entry);
            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            SignatureFactory::create('synthetic-signing-key')->validateRequest('picture.png', $params);
            self::assertSame($params['w'].'w', $width);
        }
    }

    public function test_a_forged_width_is_a_client_error(): void
    {
        $signer = SignatureFactory::create('synthetic-signing-key');
        $params = ['w' => 320, 'fm' => 'png'];
        $params['s'] = $signer->generateSignature('picture.png', $params);
        $params['w'] = 9999;
        $request = Request::create('/img/picture.png', 'GET', $params);
        $request->setRouteResolver(static fn () => new class {
            public function parameter($name, $default = null) { return $name === 'path' ? 'picture.png' : $default; }
        });
        $this->expectException(SecurityException::class);
        (new GlideSecurityMiddleware())->handle($request, static fn () => response('must not run'));
    }

    public function test_a_missing_image_is_404_instead_of_an_empty_200(): void
    {
        Storage::fake('fixture-images');
        config(['imaginator.loaders' => [['source_disk' => 'fixture-images', 'cache_disk' => 'fixture-images', 'get_image_class' => \Omatech\Imaginator\Repositories\ImageExtractor::class]]]);
        $this->expectException(NotFoundHttpException::class);
        (new Imaginator())->getProcessedImage('absent.png', ['w' => 20]);
    }

    public function test_glide4_returns_the_transformed_bytes_and_cache_headers(): void
    {
        Storage::fake('fixture-images');
        $image = imagecreatetruecolor(40, 30);
        ob_start(); imagepng($image); $bytes = ob_get_clean();
        Storage::disk('fixture-images')->put('picture.png', $bytes);
        config(['imaginator.loaders' => [['source_disk' => 'fixture-images', 'cache_disk' => 'fixture-images', 'get_image_class' => \Omatech\Imaginator\Repositories\ImageExtractor::class]]]);
        $response = (new Imaginator())->getProcessedImage('picture.png', ['w' => 20]);
        ob_start(); $response->sendContent(); $result = ob_get_clean();
        self::assertSame(200, $response->getStatusCode());
        self::assertSame('image/png', $response->headers->get('Content-Type'));
        self::assertSame([20, 15], array_slice(getimagesizefromstring($result), 0, 2));
        self::assertStringContainsString('public', $response->headers->get('Cache-Control'));
    }
}
