# SourceWatcher [![codecov](https://codecov.io/gh/TheCocoTeam/SourceWatcher/branch/master/graph/badge.svg)](https://codecov.io/gh/TheCocoTeam/SourceWatcher) [![SourceWatcher Travis CI](https://travis-ci.com/TheCocoTeam/SourceWatcher.svg?branch=master)](https://travis-ci.com/github/TheCocoTeam/SourceWatcher) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/TheCocoTeam/SourceWatcher/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/TheCocoTeam/SourceWatcher/?branch=master)
Project which allows to watch different sources of data

## Docker container

Build the Docker image:

```shell
sudo docker-compose -f docker-compose.yaml build
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
