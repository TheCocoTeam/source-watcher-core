# Source Watcher Core [![codecov](https://codecov.io/gh/TheCocoTeam/source-watcher-core/branch/master/graph/badge.svg)](https://codecov.io/gh/TheCocoTeam/source-watcher-core) [![travis-ci](https://travis-ci.com/TheCocoTeam/source-watcher-core.svg?branch=master)](https://travis-ci.com/github/TheCocoTeam/source-watcher-core) [![scrutinizer-ci](https://scrutinizer-ci.com/g/TheCocoTeam/source-watcher-core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/TheCocoTeam/source-watcher-core/?branch=master)

PHP project which allows extracting, transform and load data from different sources including databases, files and services.

## Docker container

Build the Docker image:

```shell
sudo docker-compose -f docker-compose.yml build
```

Alternatively, you can simply build the Docker image with:

```shell
sudo docker-compose build
```

An optional step, list the Docker images to confirm it's there:

```shell
sudo docker image ls
```

Start your Docker container:

```shell
sudo docker-compose up
```

You should be able to access http://localhost:8080/
