#!/usr/bin/python
import sys,os

if len(sys.argv) != 2 :
	print "./makexml.py filename"
else:
	filename = sys.argv[1]
	
	filesize = 200000

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


		outputfile.write("<unit loop=1 keep_alive=1>\n<req>\n<url>/mcenter/n?%s</url>\n<host>:8000</host>\n<method>GET</method>\n<referer>http://:8000/</referer>\n<user-agent>Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.0.12) Gecko/2009070611 Firefox/3.0.12</user-agent>\n<accept>text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8</accept>\n<accept_lan>Accept-Language: zh-cn,zh;q=0.5</accept_lan>\n<accept_enc>gzip,deflate</accept_enc>\n<accept_char>gb2312,utf-8;q=0.7,*;q=0.7</accept_char>\n<connection>keep-alive</connection>\n<Cookie>BAIDUID=016892E17A16B2FC631F9D94F3B6A621:FG=1;USERID=e7bd903cf497190cd4dfaf; MCITY=-131:</Cookie>\n<content_type>application/x-www-form-urlencoded</content_type>\n</req>\n</unit>\n"%(eachLine[:len(eachLine)-1]))


	outputfile.close()
