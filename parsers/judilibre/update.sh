#!/bin/bash

cd $(dirname $0)

bash download_update.sh | while read file ; do
	bash parse.sh "$file"
done
