m_path=`pwd`
#m_path=/home/map/apps/servertest/lighttpd/htdocs/mysite
#echo $m_path
LinePro=`grep -n  "Pro=" $m_path/casemng/common/runone.php|awk -F: '{print $1}'`
LineClassName=`grep -n  "class=" $m_path/casemng/common/runone.php|awk -F: '{print $1}'`

#reqStr="require_once \"$m_path/casemng/case/$1/Case_$2.php\";"
claName="\$class=\"case_$2\";"
proName="\$Pro=\"$1\";"
echo $proName
sed -i ""$LineClassName"c"$claName"" $m_path/casemng/common/runone.php
sed -i ""$LinePro"c"$proName"" $m_path/casemng/common/runone.php

time=`date`
echo "----------"$time"----------" >> $m_path/casemng/case/$1/Logs.txt
#/home/map/php/bin/php $m_path/casemng/common/runone.php >>  $m_path/casemng/case/$1/Log.txt
/home/map/php/bin/php $m_path/casemng/common/runone.php >  $m_path/casemng/case/$1/log_$2.txt
cat $m_path/casemng/case/$1/log_$2.txt >> $m_path/casemng/case/$1/Logs.txt
if [ `cat $m_path/casemng/case/$1/log_$2.txt |grep failed |wc -l` -gt 0 ]; then
        echo "failed"
        exit 1;
fi
echo "passed"
