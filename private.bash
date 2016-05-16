#!/bin/bash
files="` egrep -li --include=*.md "'private'" markdown/*`"
while read -r line; do
	line="./$line"
	filenamestripped=`echo $line | cut -f 3 -d'/'`
	mv $line ./private/${filenamestripped}
	#mv $line ./private/
done <<< "$files"
	
	
	
