# nova-persian-date
Laravel Nova Persian Date Field

## Installation
You can install the package via composer:

```bash
composer require juniora/nova-persian-date-field
```
The package will automatically register itself.

## Examples
```php
PersianDate::make('Join Date', 'created_at')
    ->min('lastmonth')
    ->max('nextday')
    ->format('jYYYY-jMM-jDD')
    ->formats([
        'FormField' => 'jYYYY/jMM/jDD HH:mm',
        'IndexField' => 'jYYYY/jMM/jDD',
        'DetailField' => 'jYYYY/jMM/jDD HH:mm'
    ])
    ->humanize()
    ->type('datetime')
    ->sortable(),
```

```css
.vpd-dir-rtl {
  font-family: 'IranSans'
}
.vpd-dir-ltr {
  font-family: 'Tahoma'
}
```

## Credits
[Vue Persian DateTime Picker](https://github.com/talkhabi/vue3-persian-datetime-picker)

## Licence
This project is licensed under the MIT License