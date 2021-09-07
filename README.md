# Документация фреймворка Codememory

### Создание проекта

```
composer create-project codememory/framework ./
```

### Ссылки на разделы
- [Соглашение об именовании](#style-guide)
- [Стиль написания кода](#style-guide)


## Структура папок
│ ── __.config__  
│ ── __app__  
│&emsp;&emsp;│ ── __Controller__  
│&emsp;&emsp;│ ── __Events__  
│&emsp;&emsp;│ ── __Listeners__  
│&emsp;&emsp;│ ── __Models__  
│&emsp;&emsp;│ ── __Orm__  
│&emsp;&emsp;│&emsp;&emsp;│ ── __Entities__  
│&emsp;&emsp;│&emsp;&emsp;│ ── __Repositories__  
│&emsp;&emsp;│ ── __Software__    
│&emsp;&emsp;│ ── __Validations__  
│ ── __bin__  
│ ── __configs__  
│ ── __kernel__   
│ ── __migrations__  
│ ── __public__  
│&emsp;&emsp;│ ── __Assets__  
│&emsp;&emsp;│ ── __Dist__  
│ ── __resource__  
│&emsp;&emsp;│ ── __Templates__  
│&emsp;&emsp;│ ── __Translations__  
│ ── __routes__  
│ ── __storage__ 


## <a name="naming-convention"></a>Соглашение об именовании

- [x] Каждый ключ в конфигурации, должен находится в `camelCase`;
####
- [x] `Bind` описывающий ключ текущей конфигурации, должен начитаться на имя текущей конфигурации и через `.` имя. Формат `{configName}.{bindName}` Например:
  ```yaml
    caching:
      binds:
        caching.path: "path"            # Внимательность на имя ключа!
        caching.history.path: "path"    # Внимательность на имя ключа!
  ```
- [x] Используйте `camelCase` для переменных, методов, и функций. Например: `$variableName`, `functionName()`;
####
- [x] У каждой константы должен быть определен модификатор доступа;
####
- [x] Пространства имен должны соответствовать [PSR-4](https://www.php-fig.org/psr/psr-4/);
####
- [x] Файл и класс данного файла должен находится в `UpperCamelCase`. Например: `ProductCreator.php`, `class ProductCreator`;
####
- [x] Абстрактный класс, должен содержать в себе префикс `Abstract`;
####
- [x] Интерфейс должен заканчиваться на суффикс `Interface`;
####
- [x] Трейт должен заканчиваться на суффикс `Trait`;
####
- [x] Исключение должно заканчиваться на суффикс `Exception`;


## <a name="style-guide"></a>Стиль написания кода
- [x] После символа `{` должна быть одна пустая строка;
####
- [x] Перед символом `}` должна быть одна пустая строка;
####
- [x] После `namespace` должна быть одна пустая строка;
####
- [x] После последнего `use` должна быть одна пустая строка;
####
- [x] Каждый класс, должен содержать в себе теги `PHPDoc` - `class {className}`, `@package {namespace}`, `@author {author}`;
####
- [x] Каждый метод, свойство, должны содержать `PHPDoc`, если метод или свойство переопределены, то в `PHPDoc` должен быть единственный тэг `@inheritDoc` за исключением тега `@throw`;
####
- [x] Для любого использующего класса должен быть `use`;
####
- [x] Не используйте двойные кавычки, вместо них воспользуйтесь одинарными, за исключением того, если вам нужно использовать управляющие последовательности. Если нужно вызвать переменную внутри строки, воспользуйтесь функцией `sprintf`;
####
- [x] Если вызывается две и более одинаковых функций, то не стоит использовать пустую строку между ними;
####
- [x] Если `service-provider` используется два и более раза, занесите данных сервис-провайдер в переменную;
####
- [x] Каждая переменная, функция, метод, должны иметь тип возврата;
####
- [x] Используйте круглые скобки при создании экземпляров классов независимо от количества аргументов конструктора;
####
- [x] После каждой `,` должен быть пробел;
####
- [x] Так же используйте стили [PSR-12](https://www.php-fig.org/psr/psr-12/), которые не переопределяют `Codememory Style Guide`;