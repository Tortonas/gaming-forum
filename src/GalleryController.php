<?php


class GalleryController extends MainController implements iController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function printPageView()
    {
        // TODO: Implement printPageView() method.

    }

    public function getTitle()
    {
        echo "Gaming forum - galerija";
    }
}