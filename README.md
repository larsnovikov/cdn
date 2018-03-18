<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Хранилище изображений web-проекта(микросервис)</h1>
    <br>
</p>

<h1>Быстрая установка</h1>
<p>
    Чтобы максимально упростить разворачивание сервера был создан репозиторий: https://github.com/larsnovikov/docker-cdn2.
</p>    

<h3>Хранение изображений</h3>
<h4>Исходные изображения</h4>
<p>
    Все исходные изображения должны находиться в папке указанной в параметре 'inputPath' конфигурации. Рекомендуется примонтировать эту папку к Вашему основному Web-приложению и просто складывать в нее загруженные изображения.
</p>
<h4>Изображения водяных знаков</h4>
<p>
    Все изображения водяных знаков должны находиться в папке указанной в параметре 'watermarkPath' конфигурации.
</p>  
<h4>Готовые изображения</h4>
<p>
    Готовые изображения должны попадать в хранилища. Хранилища - это директории находящиеся в папке указанной в параметре 'outputPath' конфигурации. Предполагается, что эти директории будут примонтированными жесткими дисками. Для сохранения изображения выбирается хранилище на котором больше свободного места.
</p>    


<h3>Конфигурирование перед началом работы</h3>
<p>
    Для указания Ip-адресов с которых разрешено принимать запросы необходимо выполнить <pre>php yii command/add-frontends</pre>
    Для указания директорий хранения переопределите параметры config/cdn.php в config/cdn-local.php
</p>

<h3>Примеры запросов</h3>

<h4>Пример запроса для кропа изображения</h4>
Отправлять запрос на URL image/upload
<pre>
[
    'source' => 'image.jpg', (1) 
    'formats' => json_encode([
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
<h5>Примечания</h5>
(1): Исходное изображение. Должно лежать в папке указанной в параметре 'inputPath' конфигурации.<br>
(2): Метод обработки. true - добавлять поля, не обрезать изображение; false - не добавлять поля, обрезать изображения. (При false поля могут быть добавлены если необходимый размер изображения больше исходника. (Никогда не растягиваем исходники)).<br>
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
<h4>Пример запроса для удаления изображения</h4>
Отправлять запрос на URL image/remove
<pre>
[
    'source' => 'storage1/remove_me.jpg'(1)
]
</pre>
<h5>Примечания</h5>
(1): Изображение для удаления. Должно лежать в папке указанной в параметре 'outputPath' конфигурации.
