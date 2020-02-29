<?php
declare(strict_types=1);

namespace App\Http\Controller\Base;

class BaseController
{
    private $layoutPath;

    public function __construct()
    {
        $this->setLayoutPath(BASE_PATH . '/views/layouts/master.phtml');
    }

    /**
     * @param string $layoutPath
     */
    public function setLayoutPath(string $layoutPath): void
    {
        $this->layoutPath = $layoutPath;
    }

    /**
     * @return $this
     */
    public function withoutLayout(): self
    {
        $this->layoutPath = null;

        return $this;
    }

    /**
     * @param string $viewPath
     * @param array $vars
     * @return mixed
     */
    public function renderView(string $viewPath, array $vars = [])
    {
        ob_start();

        if ($vars) {
            extract($vars, EXTR_SKIP);
        }

        extract(['contentView' => $viewPath]);

        if ($this->layoutPath === null) {
            return include_once $viewPath;
        }

        return include_once $this->layoutPath;
    }
}
