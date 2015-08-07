<?php

class SculpinKernel extends \Sculpin\Bundle\SculpinBundle\HttpKernel\AbstractKernel
{
    protected function getAdditionalSculpinBundles()
    {
        return array(
           'Fab\Sculpin\Bundle\PagesBundle\SculpinPagesBundle'
        );
    }
}
