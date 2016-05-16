#!/bin/bash
for filename in ./pages/*.html; do
	filenamestripped=`echo $filename | cut -f 3 -d'/' | sed 's/.html//g'`
	#echo $filenamestripped
	
	cat $filename | pandoc -f html -t markdown_github > "./markdown/${filenamestripped}.md"
done
	
