#!/bin/bash
reportfile="report"`date "+%Y-%m-%d %H:%M:%S"`
for file in `ls ./case/*/*`
do
	path=`pwd`"/"$file
	class=`echo $file | awk -F "/" '{print $4}' | awk -F "." '{print $1}' `
	/home/map/php/bin/php ./common/async_run.php $path $class > reportfile &
done

