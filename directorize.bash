#!/bin/bash
cd ~/src/mindtouch-dump/
files="`ls markdown/*.md`"

while read -r line; do
	cd ~/src/mindtouch-dump/organized
	line="./$line"
	filename=`echo $line | cut -f 3 -d'/'`
	echo $filename
	arr=$(echo $filename | sed 's/%2F/\'$'\n/g')
	for x in $arr
	do
		if [[ $x == *".md" ]]; then
			cp ~/src/mindtouch-dump/markdown/$filename $x
			#echo "file: $x"
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
	
