#!/usr/bin/python
import sys,os

if len(sys.argv) != 2 :
	print "./makexml.py filename"
else:
	filename = sys.argv[1]
	
	filesize = 20000

	file = open(filename, 'r')
	allLines = file.readlines()
	file.close()

	outputfile = open(filename+".0",'w')
#sparks = 0;

	count=0

	for eachLine in allLines:
		count = count+1

		if count%filesize == 0:
                	outputfile.close()
        	        print "%s.%s is ok"%(sys.argv[1],count/filesize-1)
	                outputfile = open(sys.argv[1]+".%s"%(count/filesize), 'w')


		outputfile.write("<unit loop=2>\n<req>\n<url>/ulog/public/up.php?</url>\n<host>db-qa-bu-qa24.db01.baidu.com:8000</host>\n<method>POST</method>\n<referer>http://db-qa-bu-qa24.db01.baidu.com:8000/</referer>\n<Accept>text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8</Accept>\n<Accept-Language>Accept-Language: zh-cn,zh;q=0.5</Accept-Language>\n<Accept-Encoding>gzip,deflate</Accept-Encoding>\n<Accept-Charset>gb2312,utf-8;q=0.7,*;q=0.7</Accept-Charset>\n<Keep-Alive>300</Keep-Alive>\n<Connection>keep-alive</Connection>\n<Content-Type>application/x-www-form-urlencoded</Content-Type>\n<Content>%s</Content>\n<Content-Length>%d<Content-Length>\n</req>\n</unit>\n"%(eachLine[:len(eachLine)-2],len(eachLine)-2))


	outputfile.close()
