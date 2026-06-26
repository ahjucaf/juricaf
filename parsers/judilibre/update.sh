#!/bin/bash

cd $(dirname $0)

bash download_update.sh $1 | while read file ; do
	if echo $file | grep deleted > /dev/null  ; then
		decision=$(echo $file | sed 's/.*\///' | sed 's/_.*//g');
		bash delete.sh $decision;
		continue;
	fi
	bash parse.sh "$file"
done
