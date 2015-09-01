#!/bin/bash


jumbo  -v 2>&1 >/dev/null || bash -c "$( curl http://jumbo.baidu.com/install_jumbo.sh )"; source ~/.bashrc
jumbo install mysql


cd /home/map/apps/env
source bin/activate
pip install -i http://pypi.douban.com/simple mysql-python


curl -X PUT 127.0.0.1:8600/cmd/stop
curl -X PUT 127.0.0.1:8601/cmd/stop
curl -X PUT 127.0.0.1:8602/cmd/stop
curl -X PUT 127.0.0.1:8603/cmd/stop
curl -X PUT 127.0.0.1:8604/cmd/stop
curl -X PUT 127.0.0.1:8605/cmd/stop

mkdir -p /home/map/apps/ccdn/rundata/log

supervisorctl -c /home/map/apps/ccexp/supervisord.conf reread
supervisorctl -c /home/map/apps/ccexp/supervisord.conf update

