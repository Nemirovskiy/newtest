## Схема проекта:
в проекте есть два типа отображения:
1. страницы по шаблонам
2. тесты - динамически формируемые страницы
* для отображения тестов используются свои шаблоны
* имеем шаблоны двух типов: для станиц и для тестов
* если в адресе нет параметра - паказать главную страницу
* 
### контролллер

#### Алгоритм работы
1. проверяем адрес методом - результат: шаблон или тема теста
2. создаем объект - страницы если предыдущий метод вернул шаблон, создаем страницу, иначе - тест
3. если есть переданные данные - обработаем и передадим дальше
4. методу объекта передаем полученные данные 
5. получаем из метода объекта данные для показа
5. полученные данные для показа передаем в представление

* метод проверки адресной строки
	* если адрес = шаблон страницы - покажем указанную страницу по шаблону
	* иначе адрес = название темы теста
* 

### Главная модель
* метод получения названий

### модель страница
* метод получения названий страниц
* метод показа страницы
	* задаем заголовок страницы по названию
	* формируем навигацию

### модель тест
* метод получения кол-ва вопросов в каждой теме (первый раз запрос к БД - сохранение в куки, последующие - берем из куки)
* метод генерации нового номера вопроса из заданной темы без повторения, если есть такая настройка
* метод 
* метод получения таблицы вопроса и ответов по указанной теме и указанному номеру вопроса

### класс База Данных
* работает без создания экземпляра класса
* метод получения списка из запроса к БД
* метод выполнения обращения к БД

### класс кукис
* работает без создания экземпляра класса
* метод записи информации в куки
* метод чтения из куки

***

#### для навигации
#####переменная содержит поля:
* active - активная ли ссылка
* name   - название ссылки
* href   - адресс ссылки
##### для отображения вопроса поля
* Theme - Тема:
  * text	-- текст названия
  * name	-- обозначение названия
* testNum 	- номер вопроса
* testQuest - Текст вопроса
* answers 	- ответы:
  * oder 	-- порядок
  * value   -- значение
  * text 	-- текст ответа