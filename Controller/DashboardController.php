<?php

namespace Wucdbm\Bundle\GalleryBundle\Controller;

use Wucdbm\Bundle\WucdbmBundle\Controller\BaseController;

class DashboardController extends BaseController {

    public function dashboardAction() {
        return $this->render('@WucdbmGallery/Dashboard/dashboard.html.twig');
    }

}