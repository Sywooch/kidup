#!/bin/sh

ps aux | grep 'php yii job/worker'
if [ $? -ne 0 ]
then
    php yii job/worker
fi