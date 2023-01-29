# Export Excel Using The Library `maatwebsite/excel` in Laravel

Install maatwebsite/excel library by composer.
```
composer require maatwebsite/excel
```

Include the service provider / facade in `config/app.php`.

```php
'providers' => [
    //...
    Maatwebsite\Excel\ExcelServiceProvider::class,
]
//...
'aliases' => [
    //...
    'Excel' => Maatwebsite\Excel\Facades\Excel::class,
]
```
If you want to overide Excel configuration, you can publish the config.

```
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider" --tag=config
```
Create Controller by the following command
```
php artisan make:controller ExportExcelController
```

Create Export Class by the following command
```
php artisan make:export UsersExport --model=User
```

Add the following routes in `routes/web.php` file.
```php
Route::get('export-users','ExportExcelController@ExportUsers')->name('export-users');
```



### Handle Download Excel Code in Controller

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\UsersExport;
use Excel;

class ExportExcelController extends Controller
{
    public function ExportUsers(Request $request){
        return Excel::download(new UsersExport,'users.csv');
    }
}
```
### Now Update UsersExport Class, implement the following Customization.
* Export Data From Collection using `FromCollection`
* Export Data With Heading using `WithHeadings`
* Change Heading Stiles using `WithStyles`
* Add Condition in Mapping Using `WithMapping` 
* Column Auto Resize Using `ShouldAutoSize`
```php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class UsersExport implements FromCollection,WithHeadings,WithStyles,WithMapping,ShouldAutoSize
{
    
    /**
    * @return \Illuminate\Support\AfterSheet
    */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:D1'; // All headers A1:Z1
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }


    /**
    * @return \Illuminate\Support\WithMapping
    */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            date('M d, Y h:i A',strtotime($user->created_at)),
        ];
    }


    /**
    * @return \Illuminate\Support\WithStyles
    */
    public function styles(Worksheet $sheet)
    {
        return [
           1    => ['font' => ['bold' => true]],
        ];
    }


    /**
    * @return \Illuminate\Support\WithHeadings
    */
    public function headings(): array
    {
        return ["id", "name", "email",'created_at'];
    }


    /**
    * @return \Illuminate\Support\FromCollection
    */
    public function collection()
    {
        return User::select('id','name','email','created_at')->get();
    }
}


```
