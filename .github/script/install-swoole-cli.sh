#!/bin/bash

set -ex

version=$1

if [ -z "${version}" ]; then
  exit 0;
fi

mkdir -p /tmp/swoole-cli

cd /tmp/swoole-cli

curl -L -o swoole-cli.tar.xz https://github.com/swoole/swoole-src/releases/download/$version/swoole-cli-$version-linux-x64.tar.xz

xz -d swoole-cli.tar.xz

tar -xvf swoole-cli.tar -C /usr/local/bin

chmod +x /usr/local/bin/swoole-cli

rm -f /usr/local/bin/php

cp /usr/local/bin/swoole-cli /usr/local/bin/php
