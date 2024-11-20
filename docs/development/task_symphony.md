# Тестове завдання 

## API

### Завдання

Розробити backend на Symphony v.6+

Проєкт повинен мати базу даних і endpoint,
в якому треба отримати дані наступного формату:

```json
{
    "username": "domo00000",
    "password": "1",
    "phone": "000000000",
    "email": "test@test.ua",
    "language": "uk",
    "theme": "light",
    "deviceId": "",
    "addresses": [
        {
            "address": "34 кв. Академіка Ломоносова, 36",
            "status": "active",
            "tariff": "Unlim 1000",
            "balance": 230,
            "services": {
                "internet": "Unlim 1000",
                "tv": "omega 60",
                "ip": "10.10.10.10"
            }
        },
        {
            "address": "25 кв. Богдана Хмельницького, 12",
            "status": "disable",
            "tariff": "Unlim 1000",
            "balance": -1,
            "services": {
                "internet": "Unlim 1000",
                "tv": "omega 60",
                "ip": "10.10.10.10"
            }
        }
    ]
}
```

### Що потрібно зробити?

* Сформувати каркас API
* Налаштувати бандли  
* Спроектувати БД згідно з json
* Написати міграції
* Написати endpoint

### Що буде оцінюватись

* Схема бази даних 
* Дотримання принципів OOP/SOLID
* Дотримання PSR

### Додаткове завдання

* JSON від замовника має помилку, на яку треба вказати  
