#!/bin/bash
rm ./cc_log_1.txt
file=$2
para=$1
hm=$3
cat /home/map/qa/liuyangyang/ccexp/apps/ccexp/rundata/log/$file | grep "$para" | grep "$hm"  > ./cc_log_1.txt
#echo $1 > ./txt
#echo "param2" >> ./txt
#echo $2  >> ./txt
#echo "param3" >> ./txt
#echo $3  >> ./txt
#echo "param4" >> ./txt
#echo $4  >> ./txt
