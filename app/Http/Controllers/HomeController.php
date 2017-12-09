<?php

namespace App\Http\Controllers;

use App\Repositories\Photo\PhotoRepository;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * @var $photoRepository
     */
    public $photoRepository;

    public function __construct(
        \Illuminate\Http\Request $request,
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
}
