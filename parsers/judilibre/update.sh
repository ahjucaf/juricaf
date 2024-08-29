#!/bin/bash

cd $(dirname $0)

bash download_update.sh $1 | while read file ; do
	bash parse.sh "$file"
done
