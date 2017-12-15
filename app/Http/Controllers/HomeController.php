<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Photo\PhotoRepository;

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
        $images = $this->photoRepository->get(1, 50, ['status' => Photo::STATUS_PUBLISHED]);

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

        /** @var \Illuminate\Validation\Validator $validator */
        $validator = Validator::make($this->request->all(), [
            'page' => 'integer|min:1',
            'per-page' => 'integer|min:3|max:60',
        ]);

        $page = $this->request->get('page', 1);
        $perPage = $this->request->get('per-page', 12);

        $photos = $this->photoRepository->get($page, $perPage, ['status' => Photo::STATUS_PUBLISHED]);

        return $this->json([
            'photos' => $photos,
        ]);
    }

}
