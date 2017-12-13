<?php

namespace App\Http\Controllers;

use App\Repositories\Photo\PhotoRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var $photoRepository
     */
    public $photoRepository;

    public function __construct(
        Request $request,
        PhotoRepository $photoRepository
    ) {
        $this->photoRepository = $photoRepository;
        parent::__construct($request);
    }

    public function indexAction()
    {
        $images = $this->photoRepository->get(1, 50, ['published' => 1]);

        return $this->render('index', [
            'images' => $images,
        ]);
    }

    public function getPhotosAction()
    {
        if (!$this->request->ajax()) {
            echo 'Not allowed.';
            die;
        }

        // TODO: validation!
        $page = $this->request->get('page', 1);
        $perPage = $this->request->get('per-page', 12);

        $photos = $this->photoRepository->get($page, $perPage, ['published' => 1]);

        return $this->json([
            'photos' => $photos,
        ]);
    }

}
