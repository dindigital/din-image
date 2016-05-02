<?php

use Din\DinImage\DinImage;

class DinImageConfigTests extends PHPUnit_Framework_TestCase
{
    protected $dinImage;

    /**
     * @expectedException        Din\DinImage\Exceptions\ConfigException
     * @expectedExceptionMessage A imagem padrão não foi definida
     */
    public function testDefaultImageException()
    {
        $this->dinImage = new DinImage([]);
    }

    /**
     * @expectedException        Din\DinImage\Exceptions\ConfigException
     * @expectedExceptionMessage O diretoório de origem não foi definido
     */
    public function testSourceFolderException()
    {
        $this->dinImage = new DinImage([
            'default_image' => 'a',
        ]);
    }

    /**
     * @expectedException        Din\DinImage\Exceptions\ConfigException
     * @expectedExceptionMessage O diretoório de destino não foi definido
     */
    public function testDestFolderException()
    {
        $this->dinImage = new DinImage([
            'default_image' => 'a',
            'source_folder' => 'b',
        ]);
    }

    /**
     * @expectedException        Din\DinImage\Exceptions\FilesystemException
     * @expectedExceptionMessage A imagem padrão não foi encontrada
     */
    public function testInvalidDefaultImageException()
    {
        $this->dinImage = new DinImage([
            'default_image' => 'a',
            'source_folder' => 'b',
            'dest_folder' => 'c',
        ]);
    }

    /**
     * @expectedException        Din\DinImage\Exceptions\FilesystemException
     * @expectedExceptionMessage O diretório de origem não existe
     */
    public function testInvalidSourceFolderException()
    {
        $this->dinImage = new DinImage([
            'default_image' => __DIR__ . '/../resources/source/default.png',
            'source_folder' => 'b',
            'dest_folder' => 'c',
        ]);
    }

    /**
     * @expectedException        Din\DinImage\Exceptions\FilesystemException
     * @expectedExceptionMessage O diretório de destino não existe ou não tem as permissão de escrita
     */
    public function testInvalidDestFolderException()
    {
        $this->dinImage = new DinImage([
            'default_image' => __DIR__ . '/../resources/source/default.png',
            'source_folder' => __DIR__ . '/../resources/source',
            'dest_folder' => 'c',
        ]);
    }
}