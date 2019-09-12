
#!/bin/bash

cd ../tars/

php ../src/vendor/phptars/tars2php/src/tars2php.php ./tarsclient.proto.php

php ../src/vendor/phptars/tars2php/src/tars2php.php ./hello.proto.php