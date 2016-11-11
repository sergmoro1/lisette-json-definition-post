<a href='#en_readme_md'>README.md in English</a>

<h1>WordPress плагин для работы с JSON определениями в post_content</h1>

<h2>Demo</h2>

<a href='http://lisette.vorst.ru'>Плагин для агентств недвижимости</a>.

<h2>Назначение</h2>
Огромное количество готовых тем и постоянное появление новых побуждает разработчиков
писать плагины для решения задач не свойственных для WordPress.
Часто, при этом, в CMS добавляются новые структуры данных.

Lisette_JDP ничего не меняет в структуре данных WordPress. Тем не менее он легко
превращает post_content в запись (модель), с которой затем работает.

Пост становится записью для конечного пользователя, с возможность задавать 
критерии выбора, подготавливать feed для транляции в сторонние сервисы.

Администратор системы тоже получает возможность работать с постом, как с записью, 
редактируя поля с помощью настраиваемой формы.

<h2>Как это сделано</h2>
Значения атрибутов записи хранятся в поле post_content в виде нотации похожей на JSON определения. 
Плагин считывает определения и преобразует их в записи "на лету".

<h2>Установка</h2>
В каталоге размещен плагин настроенный для работы агенства недвижимости.
Возможна и любая другая настройка. Как именно это может быть сделано, смотрите ниже.

Установка обычная для WordPress - скачать, распаковать, положить в каталог плагинов и активировать.

<h2>Настройка</h2>
В отличии от большинства плагинов для WordPress плагин Lisette_JDP не имеет средств настройки
в административной панели за исключением пары виджетов - формы критериев поиска и карты.

Настройки необходимо проводить в файлах конфигурации, файлах форм. То есть так, как это делают программисты.
Фактически, при этом, будет создана своя реализация данного плагина.

Что можно настраивать?

Если вы хотите изменить поля записи

1. Атрибуты (поля) записи, которые вы хотели бы заполнять находятся в файле ./config/new_ad.json.
Формат - имя_поля: значение по умолчанию.
2. Приведите в соответствие с произведенными изменениями файлы ./config/fields.php, ./config/select_options.php, ./config/categories.php.
3. Форму ./views/edit.php предназначенную для редактирования полей в административной панели. 
И другие представления, уже для frontend, расположенные в этой же папке.
4. Изменить обработчики событий, для реакции на действия пользователя на стороне клиента, в файле ./js/start.js.

Если вы хотите изменить форму задания критериев поиска
1. Измените форму ./views/criteria.php предназначенную для ввода критериев поиска.
2. Класс наследующий от LisetteJDPApplication.php. Для данного плагина это RealtyApplication.php.
Необходимо переопределить методы format, condition, yaPoint приведя их в соответствие с новыми атрибутами.



<h1><a name='en_readme_md'></a>WordPress plugin for working with JSON definitions in a post content</h1>

<h2>Demo</h2>

<a href='http://lisette.vorst.ru'>Plugin for Real Estate Agencies</a>.

<h2>Purpose</h2>
A huge number of ready themes and the constant emergence of new encourages developers 
to write plugins for tasks not typical for WordPress. Often there are added a new data structures to CMS.

Lisette_JDP does not change anything in the data structure of WordPress. However, it is easy
turns post_content to the record (model), which then works.

The post becomes a record for the end user, with the opportunity to ask 
selection criteria or to prepare feed for transmission in third-party services.

The system administrator also gets the opportunity to work with the post how to record 
editing fields using custom forms.

<h2>How it's made</h2>
The attribute values of records are stored in post_content field in the form of notation like JSON definition. 
The plugin reads the definitions and converts them into records on the fly.

<h2>Installation</h2>
The catalogue includes a plug configured for real estate agencies.
Possible any other setting. How this can be done, see below.

Installation the usual for WordPress - download, unzip, put it in the plugins directory and activate.

<h2>Setting</h2>
Unlike most plug-ins for WordPress plugin Lisette_JDP has no configuration tools
in the administration panel exept two widgets - criteria form and map.

The customizations should be done in the configuration files and forms files. 
That is the way it is done by programmers.

What can be customized?

If you want to change fields of the record

1. Attributes (fields) of records you would like to fill are in the file ./config/new_ad.json.
Format is field_name: value by default.
2. Given in accordance with the modifications to the files ./config/fields.php, ./config/select_options.php, ./config/categories.php.
3. Form ./views/edit.php designed to edit the fields in the administration panel. 
And other views for frontend, is located in the same folder.
4. To change the event handlers to respond to user actions on the client side in the file ./js/start.js.

If you want to change the form of the search criteria

1. Change form ./views/criteria.php used to enter search criteria.
2. The class inherits from LisetteJDPApplication.php. For this plugin is RealtyApplication.php.
You need to override methods, format, condition, yaPoint to bring them into conformity with the new attributes.

