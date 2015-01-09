[PHP] LAZY LOADING - LOAD IT WHEN YOU NEED IT
=============================================

Beberapa hari yang lalu gw baca-baca dokumentasi [Slim framework](http://docs.slimframework.com/), disitu pikiran gw tertuju ke bagian [Dependency Injection](http://docs.slimframework.com/#DI-Overview).

Soal dependency injection sendiri gw ngerti lah dikit-dikit, intinya itu teknik memasukkan(inject)
value(biasanya berupa objek lain) kedalam sebuah objek.

```php
// contoh dependency injection
$mobil = new Mobil();

// inject objek KacaDepan ke mobil
$mobil->kacaDepan = new KacaDepan();
```

Tapi yang menarik dari dokumentasi slim itu di bagian resource locator sama inject singleton. 
Jadi dia tuh inject value ke dalam objek appnya begini:

```php
// Resource Locator
$app = new Slim\Slim();

// inject objek foo
$app->foo = function() {
  return new Foo;
};

// akses objek foo
var_dump($app->foo);
```

```php
// Inject Singleton
$app = new Slim\Slim();

// inject objek foo
$app->container->singleton(function() {
  return new Foo;
});

// akses objek foo
var_dump($app->foo);
```

Nah yang jadi pertanyaan, kenapa objek mesti disimpen ke [Closure/function](http://php.net/manual/en/functions.anonymous.php), dimana Closurenya juga cuma ngereturn objek Foo

misal untuk singleton, sebenernya begini juga bisa:

```php
$app = new Slim\Slim();
$app->foo = new Foo;
```

singkat dan lebih mudah dimengerti kan?

Dari situ gw penasaran, repo yang udah dapet [4k+ stargazers](https://github.com/codeguy/Slim/stargazers) nggak
mungkin buat begitu tanpa alasan. Gw googling lah tentang "PHP Dependency Injection", 
tapi belum nemu hal yang mengarah kesana. 
Dan akhirnya gw inget kalo nggak salah symfony juga 
sering bahas DI Container di [documention booknya](http://symfony.com/doc/current/index.html). Dan bener aja, 
di buku itu gw nemu istilah yang sebenernya pernah gw denger di forum, tapi gw cuekin. 
Istilah itu namanya "**Lazy Loading**".

### LAZY LOADING

Setelah nemu istilah ini, gw langsung googling dan dianterin ke [wikipedia](http://en.wikipedia.org/wiki/Lazy_loading). Disitu dibilang **lazy loading** teknik pada pemrograman untuk menunda aplikasi memanggil suatu objek sampai saatnya objek itu dibutuhin.

Yap itu jawabannya, kenapa objek dibungkus di Closure. Buat di framework yang semua request tertuju ke index.php (alias nanganin request app via routing) ini berguna untuk menghemat resource. 
Jadi misal dalam kasus slim tadi, gw punya rute `GET /foo` dan `GET /bar`, 
dimana gw butuh objek `Foo` hanya di `/GET foo` tapi nggak di `GET /bar`, 
gw tinggal inject objek `foo` via `Closure`, dan gw bisa pake `$app->foo` itu di aksi-aksi `/GET foo`, sedangkan
di rute-rute yang nggak gunain `$app->foo`, objek `Foo` itu nggak di load.

Jadi intinya **LOAD IT WHEN YOU NEED IT**

