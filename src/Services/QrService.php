<?php

namespace App\Services;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Builder\BuilderInterface;

class QrService
{
   protected $builder;
public function __construct(BuilderInterface $builder)
{
    $this->builder = $builder;
}


public function qrcode()
{
    $rand = chr(mt_rand(ord('A'), ord('Z'))).  sprintf('%02d', mt_rand(0,999));
    $result = $this->builder
        ->size(150)
        ->margin(20)
        ->data('isgostage.000webhostapp.com')
        ->labelText('Minho')
        ->build();
        $img =$rand . '.' . 'png';
        $result->saveToFile( (\dirname(path:__DIR__, levels:2).'/public/assets/qrcode/'.$img));
        return $result->getDataUri();
}




















    
}