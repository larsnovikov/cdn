<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Хранилище изображений web-проекта(микросервис)</h1>
    <br>
</p>

<h3>Концепция доступа к изображениям</h3>
<p>
    Все запросы к изображениям должны поступать на nginx-балансировщик. Nginx-балансировщик распределяет запросы между nginx-бекендами, которые собственно берут изображения из хранилищ и отдают их в web.
</p>    

<h3>Концепция хранения изображений</h3>
<h4>Исходные изображения</h4>
<p>
    Все исходные изображения должны находиться в папке указанной в параметре 'inputPath' конфигурации. Рекомендуется примонтировать эту папку к Вашему основному Web-приложению и просто складывать в нее загруженные изображения.
</p>  
<h4>Готовые изображения</h4>
<p>
    Готовые изображения должны попадать в хранилища. Хранилища - это директории находящиеся в папке указанной в параметре 'outputPath' конфигурации. Предполагается, что эти директории будут примонтированными жесткими дисками на других серверах. Для сохранения изображения выбирается хранилище на котором больше свободного места.
</p>    


<h3>Конфигурирование</h3>
<p>
    Перед работой необходимо выполнить `php yii command/init` для создания локальной конфигурации.
    Перед работой необходимо выполнить `php yii command/add-frontends` для указания Ip-адресов с которых разрешено принимать запросы.
</p>    
<h5>Примечания</h5>
(1): Путь к папке в которую укладываются исходники для кропа.<br>
(2): Путь к папке с хранилищами.<br>
(3): Путь к папке в которой хранятся изображения водяных знаков.<br>
(4): Список IP-адресов с которых разрешено принимать запросы.<br>

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
<h4>Пример запроса для удаления изображения</h4>
Отправлять запрос на URL image/remove
<pre>
[
    'source' => 'storage1/remove_me.jpg'(1)
]
</pre>
<h5>Примечания</h5>
(1): Изображение для удаления. Должно лежать в папке указанной в параметре 'outputPath' конфигурации.
