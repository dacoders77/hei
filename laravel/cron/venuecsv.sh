#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null 2>&1 && pwd )"
for i in {1..400}
do
php $DIR/../artisan sendvenuecsvs $i
done