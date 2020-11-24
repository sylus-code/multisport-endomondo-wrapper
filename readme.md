# Endomondo Api Wrapper

#### Description
As Endomondo is going retired, I've decided to create a tool to manage trainings based on Endomondo training archive json files.

#### Quick start
```php
use SylusCode\MultiSport\EndomondoWrapper\WorkoutImporter;
use SylusCode\MultiSport\EndomondoWrapper\WorkoutParser as EndoParser;
use SylusCode\MultiSport\EndomondoWrapper\WorkoutTypeResolver as EndoTypeResolver;
use Symfony\Component\Finder\Finder;

$finder = new Finder();
$endoResolver = new EndoTypeResolver();
$endoParser = new EndoParser($endoResolver);
$endoWorkoutImporter = new WorkoutImporter($finder, $endoParser);

$path = 'endomondo-2020-11-18.zip';
$result = $endoWorkoutImporter->importFromZipFile($path);
  
var_dump($result);

// Example output:
array(1) {
[0]=>
object(SylusCode\MultiSport\Workout\Workout)#2064 (14) {
["time":"SylusCode\MultiSport\Workout\Workout":private]=>
NULL
["type":"SylusCode\MultiSport\Workout\Workout":private]=>
object(SylusCode\MultiSport\Workout\Type)#62947 (2) {
  ["id":"SylusCode\MultiSport\Workout\Type":private]=>
  int(5)
  ["name":"SylusCode\MultiSport\Workout\Type":private]=>
  string(9) "Siłownia"
}
["distance":"SylusCode\MultiSport\Workout\Workout":private]=>
float(0)
["calories":"SylusCode\MultiSport\Workout\Workout":private]=>
int(63)
["durationTotal":"SylusCode\MultiSport\Workout\Workout":private]=>
int(919)
["points":"SylusCode\MultiSport\Workout\Workout":private]=>
array(918) {
  [0]=>
  object(SylusCode\MultiSport\Workout\Point)#2066 (7) {
    ["time":"SylusCode\MultiSport\Workout\Point":private]=>
    object(DateTime)#2055 (3) {
      ["date"]=>
      string(26) "2020-11-17 09:16:32.000000"
      ["timezone_type"]=>
      int(2)
      ["timezone"]=>
      string(1) "Z"
    }
    ["latitude":"SylusCode\MultiSport\Workout\Point":private]=>
    NULL
    ["longtitude":"SylusCode\MultiSport\Workout\Point":private]=>
    NULL
    ["altitude":"SylusCode\MultiSport\Workout\Point":private]=>
    NULL
    ["distance":"SylusCode\MultiSport\Workout\Point":private]=>
    NULL
    ["heartRate":"SylusCode\MultiSport\Workout\Point":private]=>
    int(72)
    ["speed":"SylusCode\MultiSport\Workout\Point":private]=>
    NULL
  }
  ..
```
#### Disclaimer 
Endomondo workout parser based on archive json files. Hashtags are not included in this parser as Endomondo do not provide hashtagged wotkouts in archive workout pack.

#### What's next
Add unit tests
