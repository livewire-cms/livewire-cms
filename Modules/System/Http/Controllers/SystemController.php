<?php

namespace Modules\System\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use BackendMenu;

use Modules\System\Classes\ImageResizer;
use Modules\LivewireCore\Exception\SystemException;
use Exception;
use Config;

class SystemController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        BackendMenu::setContext('Modules.System', 'system');

        return view('system::index');
    }

     /**
     * Resizes an image using the provided configuration
     * and returns a redirect to the resized image
     *
     * @param string $identifier The identifier used to retrieve the image configuration
     * @param string $encodedUrl The double-encoded URL of the resized image, see https://github.com/octobercms/october/issues/3592#issuecomment-671017380
     * @return RedirectResponse
     */
    public function resizer(string $identifier, string $encodedUrl)
    {

        $resizedUrl = ImageResizer::getValidResizedUrl($identifier, $encodedUrl);
        // dd($resizedUrl);
        if (empty($resizedUrl)) {
            return response('Invalid identifier or redirect URL', 400);
        }

        // Attempt to process the resize
        try {
            $resizer = ImageResizer::fromIdentifier($identifier);

            $resizer->resize();
        } catch (SystemException $ex) {
            // If the resizing failed with a SystemException, it was most
            // likely because it is in progress or has already finished
            // although it could also be because the cache system used to store
            // configuration data is broken
            if (Config::get('cache.default', 'file') === 'array') {
                throw new Exception('Image resizing requires a persistent cache driver, "array" is not supported. Try changing config/cache.php -> default to a persistent cache driver.');
            }
        } catch (Exception $ex) {
            // If it failed for any other reason, restore the config so that
            // the resizer route will continue to work until it succeeds
            if ($resizer) {
                $resizer->storeConfig();
            }

            // Rethrow the exception
            throw $ex;
        }


        return redirect()->to($resizedUrl);
    }
}
