#!/bin/bash
FROM=iso-8859-1
TO=UTF-8
ICONV="iconv -f $FROM -t $TO"
# Convert
find . -type f -name "*.php" -maxdepth 1 | while read fn; do
cp ${fn} ${fn}.bak
$ICONV < ${fn}.bak > ${fn}
done
