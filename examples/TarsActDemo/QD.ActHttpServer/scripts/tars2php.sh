
#!/bin/bash

cd ../tars/

php ../src/vendor/phptars/tars2php/src/tars2php.php ./userInfo.proto.php
php ../src/vendor/phptars/tars2php/src/tars2php.php ./actComment.proto.php
php ../src/vendor/phptars/tars2php/src/proto2php.php ./actCommentPb.proto.php
