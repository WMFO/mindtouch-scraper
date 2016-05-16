#!/bin/bash
cd ~/src/mindtouch-dump/
files="`ls pages/ | grep .files`"

while read -r line; do
	cd ~/src/mindtouch-dump/files/
	#line="./pages/$line"
	#filename=`echo $line | cut -f 3 -d'/'`
	filename=`echo $line`
	destfile=`echo $filename | sed 's/.files//g' | sed 's/%2F/\//g'`
	echo "[Page Attachments](https://wiki-files.wmfo.org/$destfile)" >> ~/src/wiki/${destfile}.md
done <<< "$files"
