# DIN Image
## Instalação usando Composer
```json
"require": {
    "dindigital/din-image": "dev-master"
}
```
## Utilizando no Laravel
Editar o arquivo ```config\app.php```:
### Service Providers
```php
Din\DinImage\DinImageServiceProvider::class
```
### Alias
```php
'DinImage' => \Din\DinImage\Facades\DinImage::class
```
### Publicar e editar config ```config/din-image.php```
```
php artisan vendor:publish
```
## Utilizando
```php
use DinImage;
```
```php
return $image = DinImage::setWidth(100)
	->setHeight(100)
	->setCommand('fit')
	->setName('Mário')
	->setImage('panda.jpg')
	->render();
```

## Utilizando Fora do Laravel

```php
//Definindo o padrão
$this->dinImage = new DinImage([
    'default_image' => __DIR__ . '/../resources/source/default.png',
    'source_folder' => __DIR__ . '/../resources/source/',
    'dest_folder' => __DIR__ . '/../resources/dest/',
]);

return $this->dinImage->setWidth(30)
	->setHeight(20)
	->setCommand('fit')
	->setName('Nome da Imagem')
	->setImage('nome-da-imagem.jpg')
	->render();
```