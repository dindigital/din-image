<?php

use Din\DinImage\DinImage;

class DinImageTests extends PHPUnit_Framework_TestCase
{
    protected $dinImage;

    public function setUp()
    {
        $this->dinImage = new DinImage([
            'default_image' => __DIR__ . '/../resources/source/default.png',
            'source_folder' => __DIR__ . '/../resources/source/',
            'dest_folder' => __DIR__ . '/../resources/dest/',
        ]);
    }

    public function testImage()
    {
        $image = $this->dinImage->setWidth(10)
            ->setHeight(10)
            ->setCommand('fit')
            ->setName('Teste de Imagem')
            ->setImage('logo.jpg')->render();

        $size = getimagesize($image);

        $this->assertEquals($size[0], 10);
        $this->assertEquals($size[1], 10);
    }

    public function testImage1()
    {
        $image = $this->dinImage->setWidth(30)
            ->setHeight(20)
            ->setCommand('fit')
            ->setName('Novo Teste de Imagem')
            ->setImage('Imagem Nome Teste Ção.JPG')->render();

        $size = getimagesize($image);

        $this->assertEquals($size[0], 30);
        $this->assertEquals($size[1], 20);
    }

    public function testImage2()
    {
        $image = $this->dinImage->setWidth(100)
          ->setHeight(100)
          ->setCommand('fit')
          ->setName('Nome da Imagem')
          ->setImage('Imagem%20Nome%20Teste Ção.JPG')->render();

        $size = getimagesize($image);

        $this->assertEquals($size[0], 100);
        $this->assertEquals($size[1], 100);
    }
    
    public function testImage3()
    {
        $image = $this->dinImage->setWidth(200)
            ->setHeight(200)
            ->setCommand('fit')
            ->setName('Nome da Imagem 2')
            ->setImage('balbalbalbaal')->render();

        $size = getimagesize($image);

        $this->assertEquals($size[0], 200);
        $this->assertEquals($size[1], 200);
    }

}