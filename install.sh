#!/bin/bash
set -x # Show the output of the following commands (useful for debugging)

echo $private_key_staging_server | base64 --decode > deploy-key

chmod 600 deploy-key
mv deploy-key ~/.ssh/id_rsa

#creating the docker image, the tag represents the environment
SNAPSHOTTAG=''
if [ $TRAVIS_BRANCH == 'develop' ]
then
  SNAPSHOTTAG='-staging'
elif [ $TRAVIS_BRANCH == 'master' ]
then
  SNAPSHOTTAG=''
else
  SNAPSHOTTAG='-snapshot'
fi
docker build -t esac/website:0.0.$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG .
docker login --username=esactravis --password=$DOCKER_PASSWORD
docker push esac/website:0.0.$TRAVIS_BUILD_NUMBER$SNAPSHOTTAG
