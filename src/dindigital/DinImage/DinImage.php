<?php

namespace Din\DinImage;

use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Din\DinImage\Exceptions\ConfigException;
use Din\DinImage\Exceptions\FilesystemException;
use Din\DinImage\Exceptions\CommandException;

class DinImage
{

    /**
     * @var array
     */
    private $config = [];

    private $width = 0;

    private $height = 0;

    private $name;

    private $command;

    private $image_source;

    private $image;

    private $intervention;

    private $watermark = array();

    public function __construct(array $config = [])
    {
        $this->config = $config;

        if (!isset($this->config['default_image'])) {
            throw new ConfigException('A imagem padrão não foi definida');
        }

        if (!isset($this->config['source_folder'])) {
            throw new ConfigException('O diretoório de origem não foi definido');
        }

        if (!isset($this->config['dest_folder'])) {
            throw new ConfigException('O diretoório de destino não foi definido');
        }

        if (!isset($this->config['url'])) {
            $this->config['url'] = '';
        }

        if (!is_file($this->config['default_image'])) {
            throw new FilesystemException('A imagem padrão não foi encontrada');
        }

        if (!is_dir($this->config['source_folder'])) {
            throw new FilesystemException('O diretório de origem não existe');
        }

        if (!is_dir($this->config['dest_folder']) || !is_writable($this->config['dest_folder'])) {
            throw new FilesystemException('O diretório de destino não existe ou não tem as permissão de escrita');
        }
    }

    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    public function setCommand($command)
    {
        $commands = ['fit', 'widen', 'heighten'];

        if (!in_array($command, $commands)) {
            throw new CommandException('Método inválido');
        }

        $this->command = $command;

        return $this;
    }

    public function setName($name)
    {
        $name = Str::limit($name, 40, '');
        $this->name = Str::slug($name);
        $this->name .= "-{$this->width}x{$this->height}x{$this->command}";

        return $this;
    }

    /**
     * @param array $watermark
     * watermark['src'] - Path completo da imagem
     * watermark['position'] - Posição, pode ser:
     *  top-left (default), top, top-right, left, center, right, bottom-left, bottom, bottom-right
     * watermark['x'] - Offset em X
     * watermark['y'] - Offset em Y
     */
    public function setWatermark(array $watermark)
    {
        $this->watermark = $watermark;

        return $this;
    }

    public function setImage($image_source)
    {
        $image_source = urldecode($image_source);
        $this->image_source = $this->config['source_folder'] . $image_source;
        
        if (!is_file($this->image_source)) {
            $this->image_source = $this->config['default_image'];
        }

        $ext = pathinfo($this->image_source, PATHINFO_EXTENSION);

        $this->image = $this->config['dest_folder'] .
            $this->name . $this->getHash();

        if (count($this->watermark))
        {
            $this->image .= 'watermark';
        }

        $this->image .= '.' . $ext;

        return $this;
    }

    public function render()
    {
        if (!is_file($this->image))
        {
            $this->intervention = new ImageManager(array('driver' => 'gd'));
            $img = null;

            switch ($this->command)
            {
                case 'fit':
                    $img = $this->intervention->make($this->image_source)->orientate()->fit($this->width, $this->height);
                    break;
                case 'widen':
                    $img =$this->intervention->make($this->image_source)->orientate()->widen($this->width);
                    break;
                case 'heighten':
                    $img = $this->intervention->make($this->image_source)->orientate()->heighten($this->height);
                    break;
            }

            if (count($this->watermark))
            {
                $img->insert(
                    $this->watermark['src'],
                    $this->watermark['position'],
                    $this->watermark['x'],
                    $this->watermark['y']
                );
            }

            $img->save($this->image, 100);

        }

        return $this->config['url'] . $this->image;
    }

    private function getHash()
    {
        $hash = md5($this->image_source);
        return '-' . Str::limit($hash, 5, '');
    }

}
