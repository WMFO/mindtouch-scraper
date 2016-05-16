#!/bin/bash
cd ~/src/mindtouch-dump/pages/
files="`find . -regex ".*\.\(jpg\|jpeg\|png\|JPG\)"`"

while read -r line; do
	cd ~/src/mindtouch-dump/files/
	#line="./pages/$line"
	#filename=`echo $line | cut -f 3 -d'/'`
	filename=`echo $line`
	destfile=`echo $filename | sed 's/.files//g' | sed 's/%2F/\//g'| sed '1s/^.//'`
	#echo https://wiki-files.wmfo.org$destfile
	dir=`dirname $destfile`
	fname=`basename $destfile`
	echo -e "\n![${fname}](https://wiki-files.wmfo.org$destfile)" >> ~/src/wiki/${dir}.md
done <<< "$files"
