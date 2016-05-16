#!/bin/bash
cd ~/src/mindtouch-dump/
files="`ls pages/ | grep .files`"

while read -r line; do
	cd ~/src/mindtouch-dump/files/
	#line="./pages/$line"
	#filename=`echo $line | cut -f 3 -d'/'`
	filename=`echo $line`
	echo $filename
	arr=$(echo $filename | sed 's/%2F/\'$'\n/g')
	for x in $arr
	do
		if [[ $x == *".files" ]]; then
			destfile=`echo $x | sed 's/.files//g'`
			#echo $destfile
			mkdir $destfile
			cd $destfile
			cp ~/src/mindtouch-dump/pages/$filename/* .
			#echo "folder: $x"
		else
			mkdir -p $x
			cd $x
			#echo "make directory: $x"
		fi
		#echo ">$x"
	done
	#mv $line ./private/${filenamestripped}
	#mv $line ./private/
done <<< "$files"
	
