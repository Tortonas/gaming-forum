#!/bin/bash
for i in `ls | grep .php`
do
  php $i
  if [ $? -gt 0 ]
  then
    exit 1
  fi
done