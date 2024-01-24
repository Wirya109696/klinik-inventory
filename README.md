
# CORE LARAVEL IMIP

Core ini dibuat untuk mempermudah proses development programmer dalam membuat aplikasi.




## Struktur Folder MVC

Controller

    app/Htpp/Controller

Models

    app/Models

Views

    resources/views

JS

    public/js

Images

    public/img


    




## Installation

Install core laravel

```bash
  clone project https://github.com/itimip/core_laravel.git
  copy env
  composer install
  php artisan key:generate
  edit name database
  Php artisan migrate:fresh
```

Run Query Create View permissions. Sesuaikan {name_table} dengan nama database di .nev

```bash

create or replace
algorithm = UNDEFINED view `{name_table}`.`list_menu_permissions` as
select
    distinct `a`.`menu_name` as `menu_name`,
    `a`.`icon` as `icon`,
    `a`.`has_child` as `has_child`,
    `a`.`has_route` as `has_route`,
    `a`.`route_name` as `route_name`,
    `a`.`order_line` as `order_line`,
    `a`.`menu_name` as `parent_name`,
    `a`.`parent_id` as `parent_id`,
    `idx`.`valuex` as `index`,
    `store`.`valuex` as `store`,
    `edits`.`valuex` as `edits`,
    `erase`.`valuex` as `erase`
from
    ((((`{name_table}`.`permissions` `a`
left join (
    select
        `a1`.`menu_name` as `menu_name`,
        'Y' as `valuex`
    from
        `{name_table}`.`permissions` `a1`
    where
        right(`a1`.`name`,
        5) = 'index') `idx` on
    (`a`.`menu_name` = `idx`.`menu_name`))
left join (
    select
        `a1`.`menu_name` as `menu_name`,
        'Y' as `valuex`
    from
        `{name_table}`.`permissions` `a1`
    where
        right(`a1`.`name`,
        5) = 'store') `store` on
    (`a`.`menu_name` = `store`.`menu_name`))
left join (
    select
        `a1`.`menu_name` as `menu_name`,
        'Y' as `valuex`
    from
        `{name_table}`.`permissions` `a1`
    where
        right(`a1`.`name`,
        4) = 'edit') `edits` on
    (`a`.`menu_name` = `edits`.`menu_name`))
left join (
    select
        `a1`.`menu_name` as `menu_name`,
        'Y' as `valuex`
    from
        `{name_table}`.`permissions` `a1`
    where
        right(`a1`.`name`,
        5) = 'erase') `erase` on
    (`a`.`menu_name` = `erase`.`menu_name`));

```
    

## Tech Stack

**Backend:** PHP Laravel

**Frontend:** Jquery, Boostrap 5