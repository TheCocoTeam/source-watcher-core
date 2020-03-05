# SourceWatcher
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

Optional step, list the Docker images to confirm it's there:

```shell
sudo docker image ls
```

Start your Docker container:

```shell
sudo docker-compose up
```

You should be able to access http://localhost:8080/
