#!/bin/bash
for i in `ls | grep .php`
do
  php $i;
done