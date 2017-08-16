#!/bin/bash
DIR=$(pwd)

docker run \
  --rm \
  --name cprocsp \
  --privileged \
  --security-opt seccomp=unconfined \
  --tmpfs /run --tmpfs /run/lock \
  -v /sys/fs/cgroup:/sys/fs/cgroup:ro \
  -v $DIR/www:/www \
  -p 8095:80 \
  -t cprocsp
