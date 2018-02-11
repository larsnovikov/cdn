<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Хранилище избражений web-проекта</h1>
    <br>
</p>

<h3>Кроп изображений</h3>

<h5>Пример запроса для кропа изображения</h5>
Отправлять запрос на URL image/upload
<pre>
[
    'source' => 'image.jpg', (1) 
    'params' => json_encode([
        'min' => [
            'name' => 'min',
            'width' => 150,
            'height' => 100,
            'margins' => true, (2) 
            'background' => [
                'color' => '000' (3) 
            ],
            'optimize' => true, (4)
            'watermark' => [ (5)
                'image' => 'logo.png', (6)
                'width' => 50, 
                'height' => 50,
                'position' => 'pos_center' (7)
            ],
        'max' => [
            'name' => 'max',
            'width' => 700,
            'height' => 500,
            'margins' => true,
            'background' => [
                'color' => '000'
            ],
            'optimize' => true,
            'watermark' =>[
                'image' => 'logo.png',
                'width' => 50,
                'height' => 50,
                'position' => 'pos_center'
            ],    
    ])
]
</pre>
<h3>Примечания</h3>
(1): Исходное изображение. Должно лежать в папке указанной в параметре 'inputPath' конфигурации.<br>
(2): Метод обработки. true - добавлять поля, не обрезать изображение; false - не добавлять поля, обрезать изображения. (При false поля могут быть добавлены если необходимый размер изобрадения больше исходника. (Никогда не растягиваем исходники)).<br>
(3): Цвет подложки. Именно этого цвета будут поля.<br>
(4): Оптимизация изображения. true - прогнать изображение через jprgoptim после кропа, false - не прогонять. (Рекомендую всегда включать, т.к. это положительно сказывается в Google Page Speed).<br>
(5): Водяные знаки.<br>
(6): Картинка водяного знака. Должна лежать в папке указанной в параметре 'watermarkPath' конфигурации, быть PNG и с прозрачным фоном.<br>
(7): Позиция водяного знака.<br>
<ul>
    <li>pos_top_left - сверху слева</li>
    <li>pos_top_right - сверху справа</li>
    <li>pos_bottom_left - снизу слева</li>
    <li>pos_bottom_right - снизу справа</li>
    <li>pos_center - прямо по центру</li>
</ul>
