#!/bin/bash
#prepath=`pwd`/easyABC
prepath=`pwd`/uploadFiles
#prepath=`pwd`
HostIp=$1
HostPort=$2
IpStr=`host -i "$HostIp"`
HostName=`echo $IpStr | awk -F' ' '{print $5}'`
strlen=`echo ${HostName} | wc -L`
One=1
strlen=$[$strlen-$One]
#echo $strlen
HostName=${HostName:0:$strlen}
file=$3
############data#################################################
url=$4
Mtype=$5
oldUrl="<url>.*</url>"
newUrlG="<url>"$url"?%s</url>"
newUrlP="<url>"$url"?</url>"
oldHost="<host>.*</host>"
newHost="<host>"$HostName":"$HostPort"</host>"
oldRef="<referer>.*</referer>"
newRef="<referer>http://"$HostName":"$HostPort"/</referer>"
oldMethod="<method>.*</method>"
if [ "$Mtype" == "get" ]; then
	newMethod="<method>GET</method>"
	sed -i "s@$oldUrl@$newUrlG@g" $prepath/file/get_makexml.py
	sed -i "s@$oldHost@$newHost@g" $prepath/file/get_makexml.py
	sed -i "s@$oldMethod@$newMethod@g" $prepath/file/get_makexml.py
	sed -i "s@$oldRef@$newRef@g" $prepath/file/get_makexml.py
	cd $prepath
	python get_makexml.py "$file"
else
	newMethod="<method>POST</method>"
	sed -i "s@$oldUrl@$newUrlP@g" $prepath/file/post_makexml.py
	sed -i "s@$oldHost@$newHost@g" $prepath/file/post_makexml.py
	sed -i "s@$oldMethod@$newMethod@g" $prepath/file/post_makexml.py
	sed -i "s@$oldRef@$newRef@g" $prepath/file/post_makexml.py
	cd $prepath
	python post_makexml.py "$file"
fi
cat $file"."* > $file"_0"
rm $file"."*
#mv $file"_0" $prepath/data/$file".0"



