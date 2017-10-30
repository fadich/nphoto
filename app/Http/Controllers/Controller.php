<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /** @var  \Illuminate\Http\Request */
    protected $request;
    protected $layout = 'layout.base';
    protected $data = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getLayout()
    {
        if ($this->layout) {
            return view($this->layout, $this->data);
        }

        return null;
    }

    public function addData(array $data)
    {
        array_merge($this->data, $data);

        return $this;
    }

    protected function render($name = null, $data = [])
    {
        $data = array_merge($this->data, $data);
        if ($this->getLayout()) {
            return $name ? $this->getLayout()->nest('content', $name, $data) : $this->getLayout();
        }
        if (!$name) {
            throw new \Exception("No layout or view set");
        }

        return $this->layout = view($name, $data);
    }

    public function redirect($to, $code = 302, $headers = [], $secure = null)
    {
        return redirect($to, $code, $headers, $secure);
    }

    public function json(array $data = [], $status = 200, array $headers = [], $options = 0)
    {
        return response()->json($data, $status, $headers, $options);
    }
}
