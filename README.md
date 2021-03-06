### Build
```
build/run.sh 
```
### Test
```
docker exec -ti php74 bin/phpunit
```
### Run
```
docker exec -ti php74 bin/console app:run /var/symfony-example-command/data
```
### Задача:
Создать консольное приложение, которое на вход получает путь к папке. 
По этому пути находится неизвестное число папок с неизвестной глубиной вложенности. 
В папках находятся файлы разных типов, среди которых есть файлы с расширением csv, 
в них хранятся данные в следующем формате: столбец с датой, и несколько измерений.

**Например:**
```
date; A; B; C
2018-03-01; 3; 4; 5.05
2018-03-01; 1; 2; 1
2018-03-01; 2; 2; -0.05
2018-03-02; 5; 7; 6.06
2018-03-03; 1; 2; 1.06
```

Нужно сохранить в результирующий файл данные сгруппированные по дате и просумированные по измерениям.

**Например:**
```
date; A; B; C
2018-03-01; 6; 8; 6
2018-03-02; 5; 7; 6.06
2018-03-03; 1; 2; 1.06
```

Размер данных таков, что ни исходные файлы, ни даже результат аггрегации не поместятся в память.
Можно использовать любые библиотеки и компоненты (например Symfony), кроме тех, что прямо решают задачу 
агрегации или сортировки больших объемов данных (например нельзя использовать БД для этих целей).


Критические участки кода покрыть тестами.
